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

    public function index(){
        $systems=System::all();
        $sections=Section::all();
        $categories=Category::all();

        return view('manage.document.index',[
            'systems'=>$systems,
            'sections'=>$sections,
            'categories'=>$categories,
        ]);
    }
    
    /**
     * Document list created by the authenticated user
     * @param $request
     */
    public function list(Request $request)
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


        return datatables($documents)->rawColumns(['archived','obsolete'])->toJson();
    }
    
    public function view($id)
    {
        $display_version=Input::get('ver');
        
        $document=Document::find($id);
        \abort_if($document==null,404);

        # Get authenticated user
        $user=auth()->user();

        # Cannot view if not administrator, creator or moderator of the document
        if($user->perm_administrator==false && $document->created_by!=$user->id && $document->moderators()->where('user_id','=',$user->id)->first()==null)
            abort(403,'You dont have a permission to do any further action to this document.');

        
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

        
        $old_versions=$document->versions()->where('id','<>',$selected_version->id)->get();
        $reference_documents=$document->reference_documents()->with('referred_document')->get();
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
            'reference_documents'=>$reference_documents,
            'attachments'=>$attachments,
            'document_action_histories'=>$document_action_histories,
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
