<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Section;
use App\System;
use App\Category;
use App\Document;
use App\Helpers\DocumentActionHistoryHelper;
use DB;

class DocumentTrashedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $systems=System::all();
        $sections=Section::all();
        $categories=Category::all();

        return view('trashed.index',[
            'systems'=>$systems,
            'sections'=>$sections,
            'categories'=>$categories,
        ]);
        
    }
    /**
     * Trashed document list
     * @param $request
     */
    public function list(Request $request)
    {
        $find=$request['find'];
        $state=$request['state'];
        
        $documents=Document::onlyTrashed();
        $documents->select([
            'documents.id',
            'documents.document_number', 
            'documents.title', 
            'documents.system_code', 
            'documents.section_code', 
            'documents.category_code', 
            'documents.serial', 
            'documents.comment', 
            'documents.keywords', 
            'documents.created_by',
            'documents.version', 
            'documents.created_at',
            'documents.updated_at',
            'documents.archived',
            'documents.obsolete',
        ]);

        $documents->with(['creator'=>function($query){
            $query->select(['id','name']);
        }]);
        $documents->with('section')
        ->with('system')
        ->with('category');


        if(!empty($state))
        {
            switch ($state) {
                case 'archived':
                    $documents->where('archived','=',true);
                    break;
                case 'obsolete':
                    $documents->where('archived','<>',true)->where('obsolete','=',true);
                    break;
                case 'active':
                    $documents->where('archived','<>',true)->where('obsolete','<>',true);
                    break;
            }
        }
        
        if(!empty($find))
        {
            $documents->where(function($query)use($find){
                $query->orWhere('document_number','like','%'.$find.'%')
                ->orWhere('title','like','%'.$find.'%')
                ->orWhere('keywords','like','%'.$find.'%');
            });
        }


        return datatables($documents)->rawColumns(['archived','obsolete'])->toJson();
    }

    /**
     * Permanently remove the document from the system
     * @param $id The database id of the document
     */
    public function permanent($id)
    {
        $document=Document::where('id','=',$id)->onlyTrashed()->first();
        abort_if($document==null,404,'Document could not be found!');
        $document->forceDelete();

        return response()->json(['message'=>'Document has been successfully deleted on the system!']);
    }
    /**
     * Restore the document from being trashed
     * @param $id The database id of the document
     */
    public function restore($id)
    {
        try {

            $document=Document::where('id','=',$id)->onlyTrashed()->first();
            abort_if($document==null,404,'Document could not be found!');

            DB::beginTransaction();
            $document->restore();
            # Save restored history
            DocumentActionHistoryHelper::restore_document($document,auth()->user());

            DB::commit();
            return response()->json(['message'=>'Document has been successfully restored!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
        
    }

}
