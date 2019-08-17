<?php

namespace App\Helpers;
use App\Document;
use App\DocumentVersionAttachment;
use App\User;
use App\DocumentVersion;
use App\DocumentApprover;
use App\DocumentReviewer;
use App\DocumentVersionRevision;
use App\Helpers\MailHelper;
use App\Section;
use App\System;
use App\Category;

class EloquentHelper 
{
    /**
     * For public viewing document eloquent
     * Fetch only those approved document current version
     */
    public static function document_public()
    {
        return Document::whereHas('active_version',function($query){
            $query->where('approved','=',true)
            ->where('released','=',true)
            ->where('reviewed','=',true);
        });
    }
    
    public static function document(User $user)
    {
        if($user->perm_administrator==false)

            return Document::where(function($query)use($user){
                $query->orWhere('created_by','=',$user->id)
                ->orWhereHas('moderators',function($query_mod)use($user){
                    $query_mod->where('user_id', '=',$user->id);
                });
            });

        else
        return Document::on();

        
    }
}
