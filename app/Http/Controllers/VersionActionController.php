<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use App\User;
use App\DocumentVersionAttachment;
use DB;
use App\Helpers\UploadHelper;
use App\DocumentVersion;
use App\Helpers\DocumentHelper;
use App\Helpers\DocumentActionHistoryHelper;
use App\Helpers\MailHelper;
use App\Helpers\DocumentContent\Util\Cast;
use App\Helpers\DocumentVersionHelper;

class VersionActionController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Release the version
     * @param $id The database id of the document version
     */
    public function release($id)
    {
        $document_version=DocumentVersion::find($id);
        abort_if($document_version==null,404,'Document version could not be found!');
        

        # Check if the version is already released
        abort_if($document_version->released==true,422,'Document version was already released!');
        # Document must be approved and reviewed before releasing
        abort_if($document_version->approved==false || $document_version->reviewed==false,404,'Could not release the document since it was not reviewed/approved yet!');

        $document=$document_version->document;

        try {
            
            # Set all the other version to not active and not current version except to the version that will updated as released
            DocumentVersion::where('document_id','=',$document->id)
            ->where('id','<>',$document_version->id)->update([
                'current'=>false,
                'active'=>false,
            ]);

            $document_version->released=true;
            $document_version->active=true;
            $document_version->released_date=\Carbon\Carbon::now();
            $document_version->save();

            DocumentActionHistoryHelper::release($document_version,auth()->user());

            return response()->json(['message'=>'Document version has been successfully released!']);
            
        } catch (\Throwable $th) {
            abort(422,$th->getMessage());
        }
    }


    /**
     * Update new version of the document
     * @param $request Holds the information of the new version
     * @param $id The database id of the document version
     */
    public function new_version(Request $request,$id)
    {
        $validation=[
            'version.effective_date'=>'date',
            'version.content'=>'required',
            'version.description'=>'required',
            'version.reviewers.*'=>'required',
            'version.approvers.*'=>'required',
        ];
        
        $this->validate($request,$validation);

        $version_data=(object)$request['version'];
      

        try {
            $user=auth()->user();
            $document=Document::find($id);
            abort_if($document==null,404,'Document cannot be found!');

            # Get the current version
            $current_version=$document->current_version;

            # Cannot create new version if there is a version that was pending for approval
            if($current_version->approved==false || $current_version->released==false || $current_version->reviewed==false)
                abort(422,"Could not create a new version since there's still a document version that needs to be reviewed,approved and released!");

            
            # Start a transaction
            DB::beginTransaction();

            # Cast the data first to remove the underscore from javascript objects
            $version_data=Cast::generalize_keys($version_data);
    
            $version=DocumentHelper::save_version(
                $document,
                $user,
                Cast::generalize_keys($version_data->content), // Cast again to verify
                Cast::generalize_keys($version_data->description), // Cast again to verify
                $version_data->effective_date,
                $version_data->expiry_date
            );
          

            $document->version=$version->version;
            $document->save();


            # Save a new list
            foreach ($version_data->reviewers as $value) {
                $user_rev=User::find($value);
                if($user_rev==null)
                    abort(404,'Reviewer not could not be found on the system');

                DocumentHelper::save_reviewer($user_rev,$version,true);
            }

            if($version->reviewers->count()!=0) # If there are reviewers
                DocumentVersionHelper::for_review($version); # Set the document version for review

            # Save a new list
            foreach ($version_data->approvers as $value) {
                $user_rev=User::find($value);
                if($user_rev==null)
                    abort(404,'Approver not could not be found on the system');

                DocumentHelper::save_approver($user_rev,$version);
            }



            


            DB::commit();
            return response()->json(['message'=>'Document version successfully saved!']);

        }catch (\Exception $e) {

            DB::rollBack();
            abort(422,$e->getMessage());
        }


    }
    public function update_version(Request $request,$id)
    {
        $validation=[
            'version.effective_date'=>'date',
            'version.content'=>'required',
            'version.description'=>'required',
            'version.reviewers.*'=>'nullable',
            'version.approvers.*'=>'nullable',
            'version.effective_date'=>'required|date',
            'save_only'=>'required|boolean',

        ];
        
        $this->validate($request,$validation);

        $version_data=(object)$request['version'];
        # Get the user
        $user=auth()->user();

        $reviewers_to_notify=[];

        $document_version=DocumentVersion::find($id);
        abort_if($document_version==null,404,'Document version cannot be found!');
        
        # Must not update if it was approved, reviewed
        abort_if($document_version->approved==true && $document_version->reviewed==true,422,'The document version could not be updated anymore!');
        abort_if((!$version_data->reviewers || !$version_data->approvers) && $request->save_only==false,422,'Could not submit the document if there are no approvers and reviewers!');


        try {

            # Start a transaction
            DB::beginTransaction();

            $document_version->content=Cast::generalize_keys($version_data->content);
            $document_version->description=Cast::generalize_keys($version_data->description);
            $document_version->effective_date=$version_data->effective_date;
            $document_version->expiry_date=$version_data->expiry_date;
            
            if($request->save_only==false) # Update only if it is submitted officially
                $document_version->creator_modified_at=\Carbon\Carbon::now();
         

            $document_version->save();

            
            # Delete first the list
            $document_version->reviewers()->whereNotIn('user_id',$version_data->reviewers)->delete();
            # Save a new list
            foreach ($version_data->reviewers as $reviewer_user_id) {

                # Add the user as reviewer if not exists on the document version as reviewer
                if(!$document_version->reviewers()->where('user_id',$reviewer_user_id)->exists())
                {
                    $user_rev=User::find($reviewer_user_id);
                    if($user_rev==null)
                        abort(404,'Reviewer not could not be found on the system');
                    else {
                        $reviewers_to_notify[]=DocumentHelper::save_reviewer($user_rev,$document_version,$request->save_only==false);
                    }
                }
                elseif($request->save_only==false)
                {
                    # Send notification to reviewer that the document was changed.
                    $reviewer=$document_version->reviewers->where('user_id',$reviewer_user_id)->first();
                    $reviewers_to_notify[]=$reviewer;

                    if($reviewer->viewed==true) # Submit notification that it was changed
                        MailHelper::document_version_changed_reviewer($reviewer); # Required to notify every time there are changes
                }
            }

            # Delete first the approver list
            $document_version->approvers()->whereNotIn('user_id',$version_data->approvers)->delete();
            # Save a new list
            foreach ($version_data->approvers as $approver_user_id) {

                # Add the user as approver if not exists on the document version as approver
                if(!$document_version->approvers()->where('user_id',$approver_user_id)->exists())
                {
                    $user_rev=User::find($approver_user_id);
                    if($user_rev==null)
                        abort(404,'Approver not could not be found on the system');
                    else
                        DocumentHelper::save_approver($user_rev,$document_version,$request->save_only);   
                }
                elseif($request->save_only==false)
                {
                    # Send notification to approver that the document was changed.
                    $approver=$document_version->approvers->where('user_id',$approver_user_id)->first();
                    if($approver->viewed==true) # Submit notification that it was changed
                        MailHelper::document_version_changed_approver($approver); # Required to notify every time there are changes
                }
            }

            # Reset the status of the version
            DocumentVersionHelper::reset_status($document_version);

            if($request->save_only==false)
            {
                DocumentVersionHelper::for_review($document_version);

                foreach ($reviewers_to_notify as $notify) {
                    MailHelper::send_email_reviewer($notify);
                }

            }


            DB::commit();

            return response()->json(['message'=>'Document version successfully updated!']);

       } catch (\Exception $e) {

            DB::rollBack();
            abort(422,$e->getMessage());
        }

    }

    public function submit_version($id)
    {
        $document_version=DocumentVersion::find($id);
        abort_if($document_version==null,404,'Document version cannot be found!');
        abort_if($document_version->for_review==true,422,'Version has already submitted!');

        try {

            DB::beginTransaction();

            foreach ($document_version->reviewers as $reviewer) {
                
                if($reviewer->viewed==true) # Submit notification that it was changed and user can be notified
                    MailHelper::document_version_changed_reviewer($reviewer); # Required to notify every time there are changes
 
                MailHelper::send_email_reviewer($reviewer);

            }

            foreach ($document_version->approvers as $approver) {
                MailHelper::document_version_changed_approver($approver);
            }

            DocumentVersionHelper::for_review($document_version);

            DB::commit();

            return response()->json(['message'=>'Document version successfully submitted!']);

        }catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }

    /**
     * Upload files to the document attachment
     * @param $request Holds the files
     * @param $id The database id of the document version
     */
    public function upload_files(Request $request,$id)
    {
        
        $this->validate($request,['files.*'=>'required|file|mimes:csv,docx,xls,xlsx,pdf,txt|max:2000']);
        
        $document_version=DocumentVersion::find($id);
        abort_if($document_version==null,404,'Document version could not be found!');
        $files=$request['files'];
        $user=auth()->user();

        try {

            DB::beginTransaction();
            foreach($files as $file)
            {
                $upload=UploadHelper::upload_file($file,$user);
                $attachment=DocumentVersionAttachment::create([
                    'version_id'=>$document_version->id, 
                    'file_id'=>$upload->id,
                ]);

                DocumentActionHistoryHelper::upload_file($attachment,$user);
            }

            DB::commit();

            return response()->json(['message'=>'File(s) has been successfully uploaded!']);

        } catch (\Exception $ex) {
            DB::rollBack();
            abort(404,$ex->getMessage());
        }
    }

    /**
     * Delete the file from the database
     * @param $id The database id of the attachment
     */
    public function remove_file($id)
    {
        $attachment=DocumentVersionAttachment::find($id);
        abort_if($attachment==null,404,'Attachment could not be found!');

        try {

            DB::beginTransaction();

            $upload=$attachment->upload;
            $upload->delete();
            $attachment->delete();
 

            DocumentActionHistoryHelper::delete_file($attachment,auth()->user());

            DB::commit();


            return response()->json(['message'=>'File has been successfully removed!']);

        } catch (\Exception $ex) {
            DB::rollBack();
            abort(404,$ex->getMessage());
        }
    }


}
