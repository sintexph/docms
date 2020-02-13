<?php

namespace App\Helpers;
use App\Document;
use App\User;
use App\DocumentVersion;
use App\DocumentApprover;
use DB;
use App\Helpers\Access;

class EloquentHelper 
{
    /**
     * For public viewing document eloquent
     * Fetch only those approved document current version
     */
    public static function document_public()
    {
        $user=auth()->user();

        # Get the table names
        $tbl_approver=with(new DocumentApprover)->getTable();
        $tbl_doc_ver=with(new DocumentVersion)->getTable();
        $tbl_doc=with(new Document)->getTable();

        # Get the approved document based on approver
        $approved_documents=DB::table($tbl_approver)
                                ->select([$tbl_doc.'.id'])
                                ->join($tbl_doc_ver,$tbl_approver.'.version_id','=',$tbl_doc_ver.'.id')
                                ->join($tbl_doc,$tbl_doc_ver.'.document_id','=',$tbl_doc.'.id')
                                ->where($tbl_doc_ver.'.approved','=',true)
                                ->where($tbl_doc_ver.'.released','=',true)
                                ->where($tbl_doc_ver.'.reviewed','=',true)
                                ->where($tbl_doc.'.archived','=',false)
                                ->orderBy($tbl_approver.'.approved_at','desc');


        $approved_documents=$approved_documents->get();
                                

        # Strip down the ids of the documents
        $document_ids=$approved_documents->map(function($q){return $q->id;})->toArray();
        $documents=Document::whereIn('id',$approved_documents->map(function($q){return $q->id;}))->orderByRaw(DB::raw("FIELD(id,".implode(",",$document_ids).")"));

        if($user==null)
            $documents->where('access',Access::_PUBLIC);
            
        return $documents;
    }
    
    public static function document(User $user)
    {
        if($user->perm_administrator==false)

            return Document::where(function($query)use($user){
                $query->orWhere('created_by','=',$user->id)
                ->orWhereHas('accessors',function($accessor)use($user){
                    $accessor->where('user_id', '=',$user->id);
                });
            });

        else
        return Document::on();
    }
}
