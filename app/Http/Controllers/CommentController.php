<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentVersionComment;
use App\DocumentVersion;
use DB;
use App\Helpers\CommentHelper;

class CommentController extends Controller
{
    /**
     * Save comment of the document version
     */
    public function save(Request $request,$id)
    {
        $this->validate($request,[
            'comment'=>'required'
        ]);

        $document_version=DocumentVersion::find($id);
        abort_if($document_version==null,404,'Document version could not be found');


        try {
            DB::beginTransaction();

            CommentHelper::save($request['comment'],$document_version,auth()->user());
           
            DB::commit();

            return response()->json(['message'=>'Comment successfully saved!']);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(422,$th->getMessage());
        }

    }
    /**
     * Save comment of the document version
     */
    public function fetch(Request $request,$id)
    {
        $document_versions=DocumentVersionComment::where('version_id','=',$id)->orderBy('created_at','desc')->get();
        return json_encode($document_versions);
    }
}
