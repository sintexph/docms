<?php

namespace App\Helpers;
use App\Document;
use App\User;
use App\DocumentActionHistory;
use App\DocumentAccessor;
use App\DocumentVersion;
use App\Reference;
use App\DocumentVersionAttachment;

class DocumentActionHistoryHelper 
{
    private static function create_history(Document $document,User $user,$content)
    {
        DocumentActionHistory::create([
            'document_id'=>$document->id, 
            'created_by'=>$user->name, 
            'content'=>$content, 
        ]);
    }

    public static function submit_version(DocumentVersion $document_version,User $user)
    {
        $document=$document_version->document;
        self::create_history($document,$user,$user->name.' has submitted the document, versioned ('.$document_version->version.') for review.');
    }

    public static function cancel_submission(DocumentVersion $document_version,User $user)
    {
        $document=$document_version->document;
        self::create_history($document,$user,$user->name.' has cancelled the submission of the document, versioned ('.$document_version->version.').');
    }

    public static function create_document(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has created the document.');
    }

    public static function new_version(DocumentVersion $document_version,User $user)
    {
        $document=$document_version->document;
        self::create_history($document,$user,$user->name.' has created a new version('.$document_version->version.') of the document.');
    }
    public static function roll_back(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has rolled back the version of the document to '.$document->version.'.');
    }
    public static function release(DocumentVersion $document_version,User $user)
    {
        self::create_history($document_version->document,$user,$user->name.' has released the version '.$document_version->version.' of the document.');
    }
    public static function change_status(Document $document,User $user,$status)
    {
        self::create_history($document,$user,$user->name.' has change the status of the document to '.$status.'.');
    }
    public static function upload_file(DocumentVersionAttachment $attachment,User $user)
    {
        $version=$attachment->version;
        $document=$version->document;
        $upload=$attachment->upload;

        self::create_history($document,$user,$user->name.' has uploaded a file named '.$upload->file_name.' in version '.$version->version.'.');
    }

    public static function delete_file(DocumentVersionAttachment $attachment,User $user)
    {
        $version=$attachment->version;
        $document=$version->document;
        $upload=$attachment->upload;
        
        self::create_history($document,$user,$user->name.' has deleted a file named '.$upload->file_name.' in version '.$version->version.'.');
    }

    public static function add_reference(Reference $reference_document,User $user)
    {
        $document=$reference_document->document; 
        
        self::create_history($document,$user,$user->name.' has added '.$reference_document->reference.' as reference.');
    }
    public static function remove_reference(Reference $reference_document,User $user)
    {
        $document=$reference_document->document;
        
        self::create_history($document,$user,$user->name.' has remove the '.$reference_document->reference.' as reference.');
    }
    public static function edit_document(Document $document,User $user)
    {
        if($document->isDirty())
        {
            $dirty_fields=$document->getDirty();
            $original_fields=$document->getOriginal();

            foreach($dirty_fields as $field=>$value)
            {
                $field_name=str_replace("_"," ",$field);
                if($field=='created_by')
                {
                    $old_user=User::find($original_fields[$field]);
                    $new_user=User::find($value);
                    
                    self::create_history($document,$user,$user->name.' has modified the '.$field_name.' value from '.$old_user->name.' to '.$new_user->name.'.');
                }
                else
                    self::create_history($document,$user,$user->name.' has modified the '.$field_name.' value from '.$original_fields[$field].' to '.$value.'.');
            }
        }
    }


    public static function update_access(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has updated the access to '.$document->access_type);
    }
    public static function add_accessor(DocumentAccessor $accessor,User $user)
    {
        $user_accessor=$accessor->user;
        $document=$accessor->document;
        
        self::create_history($document,$user,$user->name.' has added '.$user_accessor->name.' as  accessor of the document.');
    }
    public static function remove_accessor(DocumentAccessor $accessor,User $user)
    {
        $user_accessor=$accessor->user;
        $document=$accessor->document;
        
        self::create_history($document,$user,$user->name.' has removed '.$user_accessor->name.' as  accessor of the document.');
    }
    
    public static function lock_document(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has locked the document.');
    }
    public static function unlock_document(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has unlocked the document.');
    }

    public static function archive_document(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has added the document to archived.');
    }
    public static function remove_archive_document(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has removed the document from archived.');
    }
    public static function put_document_to_trash(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has put the document in trash.');
    }
    public static function restore_document(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has restored the document from being trashed.');
    }
}
