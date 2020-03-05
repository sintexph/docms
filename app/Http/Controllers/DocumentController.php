<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use App\DocumentVersionAttachment;
use DB;
use App\Helpers\UploadHelper;

class DocumentController extends Controller
{
    public function index()
    {
        $documents=Document::select(['title','id'])->get();

        return view('documents.index',[
            'documents'=>$documents
        ]);
    }
    

    /**
     * View the document
     * @param $id The database id of the document
     */
    public function view($id)
    {
        die();
        $document=Document::find($id);

        \abort_if($document==null,404);

        return view('documents.view',['document'=>$document]);
    }


}
