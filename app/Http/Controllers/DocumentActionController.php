<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\User;
use App\System;
use App\Section;
use App\Document;
use App\Category;
use App\DocumentDraft;
use App\DocumentAccessor;
use App\Reference;
use App\DocumentModerator;
use App\DocumentVersionAttachment;
use App\Helpers\UploadHelper;
use App\Helpers\DocumentHelper;
use App\Helpers\DocumentActionHistoryHelper;
use Illuminate\Support\Facades\Input;
use App\Helpers\DocumentContent\Util\Cast;
use App\Helpers\DocumentVersionHelper;

class DocumentActionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    
    /**
     * Update the access of the document
     */
    public function update_access(Request $request,$id)
    {
        $this->validate($request,[
            'access'=>'required',
            'accessors.*'=>'nullable',
        ]);

        $document=Document::find($id);
        abort_if($document==null,404,'Document could not be found!');

        $user=auth()->user();

        try {
            DB::beginTransaction();

            $document->access=$request['access'];

            
            if($document->isDirty())
            {
                # Save action history
                DocumentActionHistoryHelper::update_access($document,$user);
            }
            $document->save();

            # Check if the access is custom
            if($document->access=="2")
            {
                
                # Save the new accessors
                foreach($request['accessors'] as $accessor_id)
                {
                    $accessor=User::find($accessor_id);
                    if($accessor==null)
                    {
                        DB::rollBack();
                        abort(422,'User could not be found on the system!');
                    }
                    
                    $document_accessor=DocumentAccessor::firstOrNew([
                        'document_id'=>$document->id,
                        'user_id'=>$accessor->id
                    ]);

                    # Check if the user is not exists on the access list
                    if($document_accessor->exists==false)
                    {
                        
                        $document_accessor->save();
                        DocumentActionHistoryHelper::add_accessor($document_accessor,$user);
                    }

                }
                
                $removed_accessors=$document->accessors()->whereNotIn('user_id',$request['accessors'])->get();
                
                foreach ($removed_accessors as $removed_accessor) {
                    # Save action history
                    DocumentActionHistoryHelper::remove_accessor($removed_accessor,$user);
                    $removed_accessor->delete();
                }

            }
            elseif($document->access=="4")
            {
                # Remove all the accessors
                $document->accessors()->delete();
                # Set the authenticated user as the accessor only
                DocumentAccessor::create([
                    'document_id'=>$document->id,
                    'user_id'=>Auth::user()->id
                ]);

            }
            else
            {
                # Remove the accessors
                $document->accessors()->delete();
            }

            
            # Save moderators if has moderators
            if(!empty($request['moderators']))
            {
                # Remove first the moderators 
                $document->moderators()->delete();

                # Save the new moderators
                foreach($request['moderators'] as $moderator_id)
                {
                    $moderator=User::find($moderator_id);
                    if($moderator==null)
                    {
                        DB::rollBack();
                        abort(404,'Moderator could not be found on the system!');
                    }

                    DocumentModerator::create([
                        'document_id'=>$document->id,
                        'user_id'=>$moderator->id,
                    ]);
                }
            }
            else
            {
                # Remove existing moderators
                $document->moderators()->delete();
            }



            

            DB::commit();

            return response()->json(['message'=>'Document access has been successfully updated!']);

        } catch (\Exception $e) {
            DB::rollBack();

            abort(422,$e->getMessage());
        }






    }

    /**
     * Save a new document with default version 1
     */
    public function save(Request $request)
    {
        $validation=[
            'document.title'=>'required',
            'document.section'=>'required',
            'document.system'=>'required',
            'document.category'=>'required',
            'document.keywords'=>'nullable|string',
            'version.content'=>'required',
            'version.description'=>'required',
            'version.reviewers.*'=>'required',
            'version.approvers.*'=>'required',
            'version.effective_date'=>'date',
        ];

        $this->validate($request,$validation);


        
        $doc_request=$request['document'];
        $document_version_request=$request['version'];

        $user=Auth::user();

        # Get the system and the section from the database
        $section=Section::where('code','=',$doc_request['section'])->first();
        $system=System::where('code','=',$doc_request['system'])->first();
        $category=Category::where('code','=',$doc_request['category'])->first();

        # Check if data exist
        abort_if($section==null || $section->system_code!=$system->code, 422,'Section is not exist on the system!');
        abort_if($system==null, 422,'System is not exist on the system!');
        abort_if($category==null, 422,'Category is not exist on the system!');
        
        try {

            DB::beginTransaction();
            
            $document=DocumentHelper::save_document(
                $user,
                $doc_request['title'],
                $system,
                $section,
                $category,
                $doc_request['keywords'],
                $doc_request['comment'],
                $doc_request['access_data']['access']
            );
            
            $document_version=DocumentHelper::save_version(
                $document,
                $user,
                Cast::generalize_keys($document_version_request['content']),
                Cast::generalize_keys($document_version_request['description']),
                $document_version_request['effective_date'],
                $document_version_request['expiry_date']
            );



            # Save a new list
            foreach ($document_version_request['reviewers'] as $value) {
                $user_rev=User::find($value);
                if($user_rev==null)
                    abort(404,'Reviewer not could not be found on the system');
                DocumentHelper::save_reviewer($user_rev,$document_version,true);
            }

            # Save a new list
            foreach ($document_version_request['approvers'] as $value) {
                $user_rev=User::find($value);
                if($user_rev==null)
                    abort(404,'Approver not could not be found on the system');
                DocumentHelper::save_approver($user_rev,$document_version);
            }

            if($document_version->reviewers->count()!=0 && $document_version->approvers->count()!=0) # If there are reviewers and approvers
            {
                $document_version->submitted=true;
                $document_version->save();
                DocumentVersionHelper::for_review($document_version); # Set the document version for review
            }
                

            # Remove the draft if the current saving has a linked draft
            $draft=DocumentDraft::find(Input::get('draft'));
            if($draft!=null)
                $draft->delete();


            
            # ------------------------------
            # ------ SET THE ACCESSORS------
            # ------------------------------

            # Check if the access is custom
            if($document->access=="2")
            {
                # Save the new accessors
                foreach($doc_request['access_data']['accessors'] as $accessor_id)
                {
                    $accessor=User::find($accessor_id);
                    if($accessor==null)
                    {
                        DB::rollBack();
                        abort(422,'User could not be found on the system!');
                    }
                    
                    DocumentAccessor::create([
                        'document_id'=>$document->id,
                        'user_id'=>$accessor->id
                    ]);
                }

            }
            elseif($document->access=="4")
            {
                # Set the authenticated user as the accessor only
                DocumentAccessor::create([
                    'document_id'=>$document->id,
                    'user_id'=>Auth::user()->id
                ]);

            }
            # ------------------------------
            # ------ END SET THE ACCESSORS------
            # ------------------------------


            DB::commit();

            return response()->json([
                'message'=>'Document has been successfully created please wait for a moment and you will be redirected to the other page!',
                'url'=>route('manage.documents.view',$document->id),
            ]);        
        } catch (\Exception $e) {

            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }

    /**
     * Update the document information
     * @param $request Holds the data to be updated on the system
     * @param $id The database id of the document
     */
    public function update_document(Request $request,$id)
    {
        $validation=[
            'title'=>'required',
            'section'=>'required',
            'system'=>'required',
            'category'=>'required',
            'keywords'=>'nullable',
        ];

        $user=auth()->user();
        $document=Document::find($id);
        abort_if(empty($document),404,'Document could not be found!');
        
        # Set the validation for creator
        if($user->can('initiate_action',$document))
        {
            $validation['created_by']='required';
            $validation['serial']='required';
        }


        $this->validate($request,$validation);

        try {
            
            DB::beginTransaction();
            
            $document->title=$request['title'];
            $document->system_code=$request['system'];
            $document->section_code=$request['section'];
            $document->category_code=$request['category'];
            $document->keywords=explode(",",$request['keywords']);
            $document->comment=$request['comment'];

            # Change the creator if the user is permitted to change
            if($user->can('initiate_action',$document))
            {
                $document->created_by=$request['created_by'];
                $document->serial=$request['serial'];
            }
            
            # Check changes
            if($document->isDirty())
            {
                if($document->isDirty('serial'))
                    abort_if(DocumentHelper::check_serial_exists($document->category,$document->serial),422,'Serial has been used already on the selected category!');

                # Record modification history
                DocumentActionHistoryHelper::edit_document($document,$user);
                # Update the document number if there are changes with the sequences like system, section....
                DocumentHelper::update_document_number($document,$user);
 
                # reset the status of the current version approver and reviewer
                DocumentVersionHelper::reset_status($document->current_version()->first());
                
                $document->save();
                DB::commit();

                return response()->json(['message'=>'Document has successfully saved!']);
            }
            else
            {
                DB::rollBack();
                return response()->json(['message'=>'No changes has been made!']);
            }
            
        } catch (\Exception $e) {

            DB::rollBack();

            switch($e->getCode())
            {
                case 23000:
                    return response()->json(['message'=>'Failed to save the document due to the document number has already been used!'],422);
                break;
                default:
                    return response()->json(['message'=>$e->getMessage()],422);
                break;
            }
            

            
        }

    }

    /**
     * Add reference to the document
     */
    public function add_reference(Request $request,$id)
    {
        $this->validate($request,[
            'reference'=>'required',
        ]);
        
        
        $document=Document::find($id);
        abort_if($document==null,404,'Document could not be found!');

        $user=auth()->user();

        try {
                
            DB::beginTransaction();

            $reference_document=new Reference;
            $reference_document->document_id=$document->id;
            $reference_document->created_by=$user->name;
            $reference_document->reference=$request['reference'];
            $reference_document->save();

            DocumentActionHistoryHelper::add_reference($reference_document,$user);

            DB::commit();
            return response()->json(['message'=>'Reference document has been successfully added!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }

    /**
     * Remove the reference
     * @param $id The database id of the reference
     */
    public function remove_reference(Request $request,$id)
    {
        $reference_document=Reference::find($id);    
        abort_if($reference_document==null,404,'Reference document could not be found!');

        $user=auth()->user();

        try {
                
            DB::beginTransaction();
            DocumentActionHistoryHelper::remove_reference($reference_document,$user);
            $reference_document->delete();

            DB::commit();
            return response()->json(['message'=>'Reference document has been successfully removed!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }

    /**
     * Roll back to nearest old version of the document
     * @param $id The database id of the document
     */
    public function roll_back($id)
    {
        $document=Document::find($id);
        abort_if($document==null,404,'Document could not be found on the system');

        try {
            
            DB::beginTransaction();

            $user=auth()->user();

            # Check if there are old versions
            if($document->old_versions->count()!=0)
            {
                # Remove first the current version
                $document->current_version()->delete();
                
                # Get the nearest old version and update it as active and current version
                $nearest_version=$document->active_version;
                $nearest_version->active=true;
                $nearest_version->current=true;
                $nearest_version->save();


                $document->version=$nearest_version->version;
                $document->save();



            }
            else {
                DB::rollBack();
                abort(422,'Failed to roll back since there are no available old versions.');
            }

            # Save action history
            DocumentActionHistoryHelper::roll_back($document,$user);


            DB::commit();
            
            return response()->json(['message'=>'Version has been successfully rolled back.']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }


    /**
     * Save the document as draft
     * @param $request Holds the document details
     * @return Message and URL for the draft
     */
    public function save_draft(Request $request)
    {
        $document_data=(Object)$request['document'];
        $document_version_data=(Object)$request['version'];

        
        

        if(!empty($document_data) && !empty($document_version_data))
        {
            $draft=DocumentDraft::create([
                'user_id'=>auth()->user()->id, 
                'document_title'=>$document_data->title, 
                'document_system_code'=>$document_data->system, 
                'document_section_code'=>$document_data->section, 
                'document_category_code'=>$document_data->category,
                'document_comment'=>$document_data->comment, 
                'document_keywords'=>$document_data->keywords, 

                'version_content'=>Cast::generalize_keys($document_version_data->content), 
                'version_description'=>Cast::generalize_keys($document_version_data->description), 
                'version_effective_date'=>$document_version_data->effective_date, 

                'version_approver_ids'=>$document_version_data->approvers,
                'version_reviewer_ids'=>$document_version_data->reviewers,

                'version_for_approval'=>$document_version_data->for_approval,
                'version_for_review'=>$document_version_data->for_review,
                
            ]);
        }

   
        
        return response()->json([
            'message'=>'Document has been successfully saved to drafts',
            'url'=>route('manage.documents.create').'?draft='.$draft->id,
        ]);
    }

    /**
     * Update existing document draft
     * @param $request Holds the document details
     * @param $id The database id of the draft
     * @return Message and URL for the draft
     */
    public function update_draft(Request $request,$id)
    {
        $draft=DocumentDraft::find($id);
        abort_if($draft==null,404,'Could not find the saved draft!');

        $document_data=(Object)$request['document'];
        $document_version_data=(Object)$request['version'];

        

        if(!empty($document_data) && !empty($document_version_data))
        {
            $draft->document_title=$document_data->title; 
            $draft->document_system_code=$document_data->system; 
            $draft->document_section_code=$document_data->section; 
            $draft->document_category_code=$document_data->category; 
            $draft->document_comment=$document_data->comment; 
            $draft->document_keywords=$document_data->keywords; 

            $draft->version_content=Cast::generalize_keys($document_version_data->content);
            $draft->version_description=Cast::generalize_keys($document_version_data->description);

            $draft->version_for_approval=$document_version_data->for_approval;  
            $draft->version_for_review=$document_version_data->for_review;   

            $draft->version_effective_date=$document_version_data->effective_date; 

            $draft->version_approver_ids=$document_version_data->approvers;
            $draft->version_reviewer_ids=$document_version_data->reviewers;
            
            
            $draft->save();

        }
        
        
        return response()->json([
            'message'=>'Document has been successfully saved to drafts',
            'url'=>route('manage.documents.create').'?draft='.$draft->id,
        ]);
    }
}
