<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use App\User;
use App\DocumentAttachment;
use DB;
use App\Helpers\UploadHelper;
use App\DocumentVersion;
use App\Helpers\DocumentHelper;
use App\Helpers\DocumentActionHistoryHelper;
use App\Helpers\MailHelper;
use App\Helpers\DocumentContent\Util\Cast;

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
        abort_if($document_version->released==true,442,'Document version was already released!');
        # Document must be approved and reviewed before releasing
        abort_if($document_version->approved==false || $document_version->reviewed==false,404,'Could not release the document since it was not reviewed/approved yet!');

        $document_version->released=true;
        $document_version->released_date=\Carbon\Carbon::now();
        $document_version->save();

        return response()->json(['message'=>'Document version has been successfully released!']);
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
            'version.for_review'=>'required',
            'version.for_approval'=>'required',
            'version.reviewers.*'=>'required',
            'version.approvers.*'=>'required',
            
        ];
        
        //$this->validate($request,$validation);

        $version_data=(object)$request['version'];
      

        try {
            $user=auth()->user();
            $document=Document::find($id);
            abort_if($document==null,404,'Document cannot be found!');

            # Get the current version
            $current_version=$document->current_version;

            # Cannot create new version if there is a version that was pending for approval
            if($current_version->approved==false || $current_version->released==false || $current_version->reviewed==false)
                abort(442,"Could not create a new version since there's still a document version that needs to be reviewed,approved and released!");

            
            # Start a transaction
            DB::beginTransaction();

      
            
            $version=DocumentHelper::save_version(
                $document,
                $user,
                Cast::generalize_keys($version_data->content),
                Cast::generalize_keys($version_data->description),
                $version_data->effective_date,
                $version_data->expiry_date,
                $version_data->for_review,
                $version_data->for_approval
            );
            
            //dump($version->content==$current_version->content);
            //DB::rollBack();
            //die();



            $document->version=$version->version;
            $document->save();


            # Save the changes and revisions
            /*
            $changes=new \App\Helpers\TextDiff($current_version->content,$version->content);
            if($changes->hasChanges())
                DocumentHelper::save_revision($version,$user,$changes->getResult());
            else
            {
                DB::rollBack();
                abort(442,'There are no changes of the document!');
            }
            */


            # Save a new list
            foreach ($version_data->reviewers as $value) {
                $user_rev=User::find($value);
                if($user_rev==null)
                    abort(404,'Reviewer not could not be found on the system');

                DocumentHelper::save_reviewer($user_rev,$version);
            }

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
            abort(442,$e->getMessage());
        }


    }
    public function update_version(Request $request,$id)
    {
        $validation=[
            'version.effective_date'=>'date',
            'version.content'=>'required',
            'version.description'=>'required',
            'version.reviewers.*'=>'required',
            'version.approvers.*'=>'required',
            'version.for_review'=>'required',
            'version.for_approval'=>'required',
            'version.effective_date'=>'required',

        ];
        
        $this->validate($request,$validation);

      
        $version_data=(object)$request['version'];



        try {
             
            $document_version=DocumentVersion::find($id);
            abort_if($document_version==null,404,'Document version cannot be found!');
        
            # Must not update if it was approved, reviewed or released
            abort_if($document_version->approved==true || $document_version->reviewed==true,404,'The document version could not be updated anymore!');

            # Get the user
            $user=auth()->user();


            # Start a transaction
            DB::beginTransaction();
            
            
            $document_version->content=Cast::generalize_keys($version_data->content);
            $document_version->description=Cast::generalize_keys($version_data->description);
            $document_version->effective_date=$version_data->effective_date;
            $document_version->expiry_date=$version_data->expiry_date;
            $document_version->for_approval=$version_data->for_approval;
            $document_version->for_review=$version_data->for_review;
              
            $document_version->save();

            /*
            # Get the last version of the version
            $last_version=$document_version->last_version();
            if($last_version!=null)
            {
                # Save the changes and revisions
                $changes=new \App\Helpers\TextDiff($last_version->content,$document_version->content);
                if($changes->hasChanges())
                    DocumentHelper::save_revision($document_version,$user,$changes->getResult());
            }
            */

            # Delete first the list
            $document_version->reviewers()->delete();
            # Save a new list
            foreach ($version_data->reviewers as $value) {
                $user_rev=User::find($value);
                if($user_rev==null)
                    abort(404,'Reviewer not could not be found on the system');

                    DocumentHelper::save_reviewer($user_rev,$document_version);
            }
            
            # Delete first the approver list
            $document_version->approvers()->delete();
            
            # Save a new list
            foreach ($version_data->approvers as $value) {
                $user_rev=User::find($value);
                if($user_rev==null)
                    abort(404,'Approver not could not be found on the system');

                    DocumentHelper::save_approver($user_rev,$document_version);
            }


            DB::commit();

            return response()->json(['message'=>'Document version successfully updated!']);

       } catch (\Exception $e) {

            DB::rollBack();
            abort(442,$e->getMessage());
        }

    }

    /**
     * Send the document version for review
     */
    public function for_review($id)
    {
        try {

            $document_version=DocumentVersion::find($id);
            abort_if($document_version==null,404,'Could not find the document version');
            abort_if($document_version->reviewed==true,442,'Document version is already reviewed!');
            abort_if($document_version->for_review==true,442,'Document version is already send for review');

            DB::beginTransaction();

            $document_version->for_review=true;
            $document_version->save();
            
            foreach ($document_version->reviewers as $reviewer) {
                # Send email to the reviewer
                MailHelper::send_email_reviewer($reviewer);
            }


            DB::commit();

            return response()->json(['message'=>'Document has been successfully sent out for review!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(442,$e->getMessage());
        }
    }
    /**
     * Send the document version for review
     */
    public function cancel_for_review($id)
    {
        try {

            $document_version=DocumentVersion::find($id);
            abort_if($document_version==null,404,'Could not find the document version');
            abort_if($document_version->reviewed==true,442,'Document version is already reviewed!');
            abort_if($document_version->for_review==false,442,'Document version is already cancelled the for review!');

            
            $document_version->for_review=false;
            $document_version->save();
            

            return response()->json(['message'=>'Document has been successfully cancelled the for review!']);

        } catch (\Exception $e) {

            abort(442,$e->getMessage());
        }
    }
    /**
     * Send the document version for approval
     */
    public function for_approval($id)
    {
        try {

            $document_version=DocumentVersion::find($id);
            abort_if($document_version==null,404,'Could not find the document version');
            abort_if($document_version->approved==true,442,'Document version is already approved!');
            abort_if($document_version->for_approval==true,442,'Document version is already send for approval');

            DB::beginTransaction();

            $document_version->for_approval=true;
            $document_version->save();
            
            foreach ($document_version->approvers as $approver) {
                # Send email to the approver
                MailHelper::send_email_approver($approver);
            }



            DB::commit();

            return response()->json(['message'=>'Document has been successfully sent out for approval!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(442,$e->getMessage());
        }
    }
    /**
     * Send the document version for approval
     */
    public function cancel_for_approval($id)
    {
        try {

            $document_version=DocumentVersion::find($id);
            abort_if($document_version==null,404,'Could not find the document version');
            abort_if($document_version->approved==true,442,'Document version is already approved!');
            abort_if($document_version->for_approval==false,442,'Document version is already cancelled the for approval!');

            
            $document_version->for_approval=false;
            $document_version->save();
            

            return response()->json(['message'=>'Document has been successfully cancelled the for approval!']);

        } catch (\Exception $e) {

            abort(442,$e->getMessage());
        }
    }
}
