<?php

namespace App\Helpers;
use App\Document;
use App\DocumentAttachment;
use App\User;
use App\DocumentVersion;
use App\DocumentApprover;
use App\DocumentReviewer;
use App\DocumentVersionRevision;
use App\Helpers\MailHelper;
use App\Section;
use App\System;
use App\Category;

class DocumentHelper 
{

    /**
     * Generate document number based on system, section and category and sequence based on the category
     */
    public static function generate_document_number(System $system,Section $section,Category $category)
    {
        $value=(Object)[
            'serial'=>'',
            'document_number'=>''
        ];

        # Generate new serial
        $value->serial=static::get_last_serial($category->code);

        # Set initial value of the document number
        $value->document_number=$system->code.'-'.$section->code.'-'.$category->code.'-'.str_pad($value->serial, 3, "0", STR_PAD_LEFT);

        return $value;
    }

    /**
     * Generate document number based on system, section and category and sequence based on the category
     */
    public static function update_document_number(Document $document)
    {
        if($document->isDirty('category_code'))
        {
            # Generate new serial
            $document->serial=static::get_last_serial($document->category_code);
        }
        
        # Set initial value of the document number
        $document->document_number=$document->system_code.'-'.$document->section_code.'-'.$document->category_code.'-'.str_pad($document->serial, 3, "0", STR_PAD_LEFT);
        $document->save();

        return $document;
    }

    /**
     * Get the last created document based on category and serial ordered by descending
     */
    public static function get_last_serial($category)
    {
        $document=Document::where('category_code',$category)->orderBy('serial','desc')->first();
        //dump($document);
        if($document==null)
            return 1;
        else
            return $document->serial+1;
    }

    /**
     * Save the document version to the database
     * @param $doc The document
     * @param $attachment The document attachment or the file
     * @param $user Who will create the version
     * @param $document_type The type of the document
     * @param $description The description of the document version
     * @param $version_number The version number or none
     * @return Instance of DocumentVersion model
     */
    public static function save_version(
        Document $doc,
        User $user,
        $content,
        $description,
        $effective_date,
        $expiry_date,
        $for_review=false,
        $for_approval=false,
        $version_number=null)
    {
        # Initialize new version of the document
        $version=new DocumentVersion;
        $version->document_id=$doc->id;

        # Auto version if the $version_number is empty
        if($version_number==null)
        {
            # Get the current version of the document
            $old_version=$doc->current_version;

            if($old_version==null)
                $version->version='1'; # Set the version to one if no old version
            else {
                $version->version=(int)$old_version->version+1; # Increment the version based on the old version
            }
        }else {
            # Set the version based on the $version_number
            $version->version=$version_number;
        }

        # Get all the versions and reset the active to false since the newest version will be the active one
        DocumentVersion::where('document_id','=',$doc->id)->where('active','=',true)->update([
            'active'=>false,
        ]);
        
        $version->content=$content;
        $version->created_by=$user->id;
        $version->description=$description;
        $version->effective_date=$effective_date;
        $version->expiry_date=$expiry_date;
        $version->for_approval=$for_approval;
        $version->for_review=$for_review;
        $version->active=true;
        $version->save();

        DocumentActionHistoryHelper::new_version($version,$user);
        

        return $version;
    }
    public static function save_reviewer(User $reviewer,DocumentVersion $version)
    {
        $data=DocumentReviewer::create([
            'user_id'=>$reviewer->id,
            'version_id'=>$version->id,
        ]);
        
        MailHelper::send_email_reviewer($data);

        return $data;
    }
    public static function save_approver(User $approver,DocumentVersion $version)
    {
        $data=DocumentApprover::create([
            'user_id'=>$approver->id,
            'version_id'=>$version->id,
        ]);
        
        MailHelper::send_email_approver($data);
        
        return $data;
    }
    /**
     * Save a new version of the document
     * 
     */
    public static function new_version(
        Document $doc,
        User $user,
        $content,
        $description,
        $effective_date,
        $expiry_date,
        array $reviewer_ids=[],
        array $approver_ids=[])
    {
        $response=[
            'message'=>'',
            'success'=>false,
        ];

        try {
            
            # Upload the file to the server
            $upload=UploadHelper::upload_file($file,$user);

            # Save the uploaded to the attachments of the document
            $attachment=DocumentAttachment::create([
                'document_id'=>$doc->id, 
                'file_id'=>$upload->id,
            ]);

            $version=self::save_version(
                $doc,
                $attachment,
                $user,
                $document_type,
                $description,
                $version_number,
                $effective_date,
                $expiry_date);
            
            # Save the reviewer
            foreach ($reviewer_ids as $id) {
                # Get the reviewer user on the database
                $reviewer=User::find($id);
                if($reviewer==null)
                {
                    $response['message']='One of the reviewer is not exists on the system!';
                    
                    return $response;
                }
                
                self::save_reviewer($reviewer,$version);
                        
            }

            # Save the approver
            foreach ($approver_ids as $id) {
                # Get the approver user on the database
                $approver=User::find($id);
                if($approver==null)
                {
                    $response['message']='One of the approver is not exists on the system!';
                    
                    return $response;
                }
                
                self::save_approver($approver,$version);
                        
            }

            # Update the latest version number of the document
            $doc->version=$version->version;
            $doc->save();


            
            $response['message']='Version successfully saved!';
            $response['success']=true;

            return $response;
            
        } catch (\Exception $e) {
 
            $response['message']=$e->getMessage();
            $response['success']=false;

            return $response;
        }


    }
    public static function save_revision(DocumentVersion $version,User $user,$content)
    {
        $document=$version->document;
        $revision=DocumentVersionRevision::firstOrCreate(['version_id'=>$version->id]);
        $revision->content=$content;
        $revision->save();
        return $revision;
    }


    public static function save_document(User $user,$title,System $system,Section $section,Category $category,$keywords,$comment,$access)
    {
        $generated_document_number=self::generate_document_number($system,$section,$category);
        $document=new Document;
        
        $document->document_number=$generated_document_number->document_number;
        $document->serial=$generated_document_number->serial;

        $document->version=1; // Set default version to 1
        $document->title=$title;
        $document->system_code=$system->code;
        $document->section_code=$section->code;
        $document->category_code=$category->code;
        $document->access=$access;
        
        $document->keywords=explode(",",$keywords);

        $document->comment=$comment;
        $document->created_by=$user->id;
        $document->save();

        DocumentActionHistoryHelper::create_document($document,$user);

        return $document;
    }

    public static function save_accessors($accessors_id)
    {
        # Save the new accessors
        foreach($request['accessors'] as $accessor_id)
        {
            $accessor=User::find($accessor_id);
            if($accessor==null)
            {
                DB::rollBack();
                abort(442,'User could not be found on the system!');
            }
            DocumentAccessor::create([
                'document_id'=>$document->id,
                'user_id'=>$accessor->id
            ]);
        }
    }
    
}
