<?php

namespace App\Helpers\Traits;

use App\Helpers\DocumentActionHistoryHelper;
use App\Document;
use Illuminate\Http\Request;
use App\Helpers\DocumentContent\Util\Cast;
use App\DocumentVersion;

trait VersionCreationTrait 
{
    protected function save_new_version(Document $document,Request $request)
    {
        $document_version_request=$request['version'];

        $document_version=$this->save_version(
            $document,
            Cast::generalize_keys($document_version_request['content']),
            Cast::generalize_keys($document_version_request['description']),
            $document_version_request['effective_date'],
            $document_version_request['expiry_date']
        );

        return $document_version;
    }

    protected function create_new_version(Document $document,DocumentVersion $document_version)
    {
        
    }

    protected function update_document_version(DocumentVersion $document_version,Request $request)
    {
        $version_data=(object)$request['version'];
   

        $document_version->content=Cast::generalize_keys($version_data->content);
        $document_version->description=Cast::generalize_keys($version_data->description);
        $document_version->effective_date=$version_data->effective_date;
        $document_version->expiry_date=$version_data->expiry_date;
            
        if($request->save_only==false) # Update only if it is submitted officially
            $document_version->creator_modified_at=\Carbon\Carbon::now();
         

        $document_version->save();
    }
    
    private function save_version(Document $doc,$content,$description,$effective_date,$expiry_date,$version_number=null)
    {
        $user=auth()->user();
        # Initialize new version of the document
        $version=new DocumentVersion;
        $version->document_id=$doc->id;

        # Auto version if the $version_number is empty
        if($version_number==null)
        {
            # Get the active version of the document
            $old_version=$doc->active_version;

            if($old_version==null)
                $version->version=1; # Set the version to one if no old version
            else
                $version->version=(int)$old_version->version+1; # Increment the version based on the old version

        }else {
            # Set the version based on the $version_number
            $version->version=$version_number;
        }

        # Get all the versions and reset the current to false since the newest version will be the current one
        DocumentVersion::where('document_id','=',$doc->id)->where('current','=',true)->update([
            'current'=>false,
        ]);
        
        $version->content=$content;
        $version->created_by=$user->id;
        $version->description=$description;
        $version->effective_date=$effective_date;
        $version->expiry_date=$expiry_date;
        $version->for_approval=false;
        $version->for_review=false;
        $version->current=true;
        $version->active=false;
        $version->creator_modified_at=\Carbon\Carbon::now();
        $version->save();

        DocumentActionHistoryHelper::new_version($version,$user);
        

        return $version;
    }

}
