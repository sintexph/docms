<?php

namespace App\Helpers;
use App\Document;
use App\User;
use App\DocumentActionHistory;
use App\DocumentAccessor;
use App\DocumentVersion;
use App\ReferenceDocument;
use App\DocumentAttachment;

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
    public static function release(Document $document,User $user)
    {
        self::create_history($document,$user,$user->name.' has rolled back the version of the document to '.$document->version.'.');
    }
    public static function change_status(Document $document,User $user,$status)
    {
        self::create_history($document,$user,$user->name.' has change the status of the document to '.$status.'.');
    }
    public static function upload_file(DocumentAttachment $attachment,User $user)
    {
        $document=$attachment->document;
        $upload=$attachment->upload;

        self::create_history($document,$user,$user->name.' has uploaded a file named '.$upload->file_name.'.');
    }

    public static function delete_file(DocumentAttachment $attachment,User $user)
    {
        $document=$attachment->document;
        $upload=$attachment->upload;

        self::create_history($document,$user,$user->name.' has deleted a file named '.$upload->file_name.'.');
    }

    public static function add_reference(ReferenceDocument $reference_document,User $user)
    {
        $document=$reference_document->document;
        $referred_document=$reference_document->referred_document;

        
        self::create_history($document,$user,$user->name.' has added the '.$referred_document->document_number.' as reference.');
    }
    public static function remove_reference(ReferenceDocument $reference_document,User $user)
    {
        $document=$reference_document->document;
        $referred_document=$reference_document->referred_document;

        self::create_history($document,$user,$user->name.' has remove the '.$referred_document->document_number.' as reference.');
    }
    public static function edit_document(Document $document,User $user)
    {
        if($document->isDirty())
        {
            $dirty_fields=$document->getDirty();
            $original_fields=$document->getOriginal();

            foreach($dirty_fields as $field=>$value)
            {
                self::create_history($document,$user,$user->name.' has modified the '.$field.' value from '.$original_fields[$field].' to '.$value.'.');
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
