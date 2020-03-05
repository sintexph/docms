<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Document;
use App\DocumentVersion;
use Illuminate\Support\Facades\Input;
use App\DocumentDraft;
use App\Section;
use App\System;
use App\Category;
use Auth;
use App\Helpers\EloquentHelper;

class ManageDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        $find=$request->find?:'';
        $state=$request->state?:'';
        $section=$request->section?:'';
        $system=$request->system?:'';
        $category=$request->category?:'';



        $systems=System::orderBy('name','asc')->get();
        $sections=Section::orderBy('name','asc')->get();
        $categories=Category::orderBy('name','asc')->get();

        return view('manage.document.index',[
            'systems'=>$systems,
            'sections'=>$sections,
            'categories'=>$categories,
            
            'find'=>$find,
            'state'=>$state,
            'section'=>$section,
            'system'=>$system,
            'category'=>$category,

        ]);
    }
    
    /**
     * Document list created by the authenticated user
     * @param $request
     */
    public function list(Request $request)
    {
        $documents=$this->source($request);
        return datatables($documents)->rawColumns(['archived','obsolete','access_icon'])->toJson();
    }

    public function download(Request $request)
    {
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=documents'.now()->format("Ymdh").'.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        fputcsv($output,[
            "#",
            "TITLE",
            "STATE",
            "DOCUMENT NO.",
            "VERSION",
            "SYSTEM",
            "SECTION",
            "CATEGORY",
            "ACCESS",
            "CREATED BY",
            "CREATED AT",
        ]);

        $documents=$this->source($request)->get();
        foreach ($documents as $document) {
            fputcsv($output,[
                $document->id,
                $document->title,
                $document->current_version->state,
                $document->document_number,
                $document->version,
                $document->system!=null?$document->system->name:$document->system_code,
                $document->section!=null?$document->section->name:$document->section_code,
                $document->category!=null?$document->category->name:$document->category_code,
                $document->access_type,
                $document->creator->name,
                $document->created_at,
            ]);
        }
    }

    /**
     * Source
     */
    private function source(Request $request)
    {
        $user=Auth::user();
        $find=$request['find'];
        $state=$request['state'];
        $system=$request['system'];
        $section=$request['section'];
        $category=$request['category'];

        $documents=Document::on();
        $documents=EloquentHelper::document($user);
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
            'documents.access',
        ]);

        $documents->with(['creator'=>function($query){
            $query->select(['id','name']);
        }]);
        $documents->with('section')
        ->with('current_version')
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

                default:
                    $documents->whereHas('current_version',function($version)use($state){
                        $version->where('state',$state);
                    });
                break;
                
            }
        }

        if(!empty($system))
            $documents->where('system_code',$system);
        if(!empty($category))
            $documents->where('category_code',$category);
        if(!empty($section))
            $documents->where('section_code',$section);

        if(!empty($find))
        {
            $documents->where(function($query)use($find){
                $query->orWhere('document_number','like','%'.$find.'%')
                ->orWhere('title','like','%'.$find.'%')
                ->orWhere('keywords','like','%'.$find.'%');
            });
        }

        return $documents;
    }
    
    
    public function view($id)
    {
        $display_version=Input::get('ver');
        
        $document=Document::find($id);
        \abort_if($document==null,404);

        # Get authenticated user
        $user=auth()->user();

        abort_if($user->can('view',$document)==false,403,"You don't have a permission to do any further action to this document.");

        
        # Default version to be displayed is the current version
        if(empty($display_version))
            $selected_version=$document->current_version();
           
        else {
            # If not empty then get the selected version
            $selected_version=DocumentVersion::where('id','=',$display_version);
        }

        $selected_version=$selected_version
        ->with(['reviewers'=>function($query){
            $query->select(['id','reviewed','reviewed_at','version_id','user_id'])->with([
                'user'=>function($user_query){
                    $user_query->select(['id','name']);
                }
            ]);
        }])
        ->with(['approvers'=>function($query){
            $query->select(['id','approved','approved_at','version_id','user_id'])->with([
                'user'=>function($user_query){
                    $user_query->select(['id','name']);
                }
            ]);
        }])
        ->first();

        abort_if($selected_version==null,404,'Document has no saved version!');

        $old_versions=$document->versions()->where('id','<>',$selected_version->id)->get();
        $references=$document->references;
        $attachments=$selected_version->attachments()->with('upload')->get();
        $document_action_histories=$document->action_histories()->orderBy('created_at','desc')->orderBy('id','desc')->paginate(10);
        
        $current_view = Input::get('view', false);
        $left_area_tab = Input::get('latab', false);
        
        
        return view('manage.document.view',[
            'document'=>$document,
            'selected_version'=>$selected_version,
            'old_versions'=>$old_versions,
            'current_view'=>$current_view,
            'left_area_tab'=>$left_area_tab,
            'references'=>$references,
            'attachments'=>$attachments,
            'document_action_histories'=>$document_action_histories,
        ]);
    }
    public function edit_current_version($id)
    {
   
        $document=Document::find($id);
        \abort_if($document==null,404);

        # Get authenticated user
        $user=auth()->user();

        # Cannot view if not administrator or creator
        if($user->perm_administrator==false && $document->created_by!=$user->id)
            abort(403,'You dont have a permission to do any further action to this document.');


        $selected_version=$document->current_version();
        $selected_version=$selected_version->with(['reviewers'=>function($query){
            $query->select(['id','reviewed','reviewed_at','version_id','user_id'])->with([
                'user'=>function($user_query){
                    $user_query->select(['id','name']);
                }
            ]);
        }])
        ->with(['approvers'=>function($query){
            $query->select(['id','approved','approved_at','version_id','user_id'])->with([
                'user'=>function($user_query){
                    $user_query->select(['id','name']);
                }
            ]);
        }])
        ->first();

        abort_if($selected_version->reviewed==true && $selected_version->approved==true,403,'The document is not available for editing.');
        abort_if($selected_version->for_review==true,422,'The document was already submitted, if you want to modify the document, please cancel the submission first!');

        return view('manage.document.edit_version_full',[
            'document'=>$document,
            'selected_version'=>$selected_version, 
        ]);
    }
    /**
     * Show the creation form of the document
     */
    public function create()
    {
        $draft_id=Input::get('draft');
        
        $draft=DocumentDraft::find($draft_id);

        if($draft_id!==null)
        {
            abort_if($draft==null,404,'Document draft could not be found');
            # only user that saved the draft can access the draft
            abort_if($draft->user_id!=auth()->user()->id,403,'You have no permission to access the document draft');
        }


        return view('manage.document.create',['draft'=>$draft]);
    }
}
