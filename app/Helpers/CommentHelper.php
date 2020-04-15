<?php

namespace App\Helpers;
use App\DocumentVersion;
use App\User;
use App\DocumentVersionComment;
use App\Helpers\MailHelper;


class CommentHelper 
{
    public static function save($comment,DocumentVersion $document_version,User $user)
    {
        $version_comment=DocumentVersionComment::create([
            
            'version_id'=>$document_version->id,
            'comment'=>$comment,
            'created_by'=>$user->id,
        ]);

        $creator=$document_version->document->creator;
        
        # Send email for comments
        MailHelper::send_email_to_comments($document_version,$user);

        return $version_comment;

    }
}
