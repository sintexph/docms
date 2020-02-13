<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentReviewer;
use App\DocumentVersion;
use DB;
use App\Helpers\DocumentVersionHelper;

class DocumentReviewingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('manage.document.reviews.index');
    }

    public function list(Request $request)
    {
        $user=auth()->user();
        $find=$request['find'];
        $state=$request['state'];

        
        
        # Versions to be reviewed
        $for_reviews=DocumentReviewer::on();
        $for_reviews->join('document_versions','document_reviewers.version_id','=','document_versions.id');
        $for_reviews->join('documents','document_versions.document_id','=','documents.id');
        $for_reviews->join('users as vcreator','document_versions.created_by','=','vcreator.id');
        $for_reviews->join('systems','documents.system_code','=','systems.code');
        $for_reviews->join('sections','documents.section_code','=','sections.code');
        $for_reviews->join('categories','documents.category_code','=','categories.code');
        $for_reviews->select([
            'document_versions.document_id',
            'document_reviewers.id',
            'document_versions.version',
            'documents.title',
            'documents.document_number',
            'document_versions.created_at',
            'vcreator.name as version_creator',
            'systems.name as system',
            'sections.name as section',
            'categories.name as category',
            'document_reviewers.version_id',
            'document_reviewers.reviewed',
        ])
        ->where('document_versions.for_review','=',true)
        ->where('document_reviewers.user_id','=',$user->id);

        switch ($state) {
            case 'rv':
                $for_reviews->where('document_reviewers.reviewed','=',true);
                break;
            case null:
                $for_reviews->where('document_reviewers.reviewed','=',false);
                break;
        }
   
        if(!empty($find))
            $for_reviews->where(function($query)use($find){
                $query->orWhere('documents.title','like','%'.$find.'%')
                ->orWhere('documents.document_number','like','%'.$find.'%')
                ->orWhere('documents.keywords','like','%'.$find.'%');
            });
            
        

        return datatables($for_reviews)->rawColumns(['reviewed'])->toJson();
    }

    /**
     * Update the document version to reviewed
     * @param $id The document version reviewer id
     */
    public function review($id)
    {
        $user=auth()->user();
        $for_review=DocumentReviewer::find($id);
        
        abort_if($for_review==null,404,'Could not find the data on the system!');

        $document_version=$for_review->document_version;

        # Must be ready for review first
        abort_if($document_version->for_review!=true,422,'Document version is not ready for reviewing!');      

        abort_if($for_review->reviewed==true,422,'Document version was been already reviewed.');
                
        # Cannot update if the user is not the reviewer of the document version
        abort_if($for_review->user_id!=$user->id,422,'You are not the reviewer of the document!');

        
        try {


            DB::beginTransaction();

            DocumentVersionHelper::review($for_review);
            
            DB::commit();

            return response()->json(['message'=>'Document version successfully reviewed!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort($e->getMessage(),422);
        }

        
        
    }
    
    /**
     * View the document version to be reviewed
     * @param $id The database id of the document version reviewer
     */
    public function view_document(Request $request,$id)
    {
        $document_reviewer=DocumentReviewer::find($id);
        abort_if($document_reviewer==null,404,'This link is already expired, please check your inbox for the new email generated from the system.');

        $user=auth()->user();
        # Check if the user is the reviewer of the document version
        abort_if($document_reviewer==null || $document_reviewer->user_id!=$user->id,403,'You have no permission to access the page');

        $document_reviewer->viewed=true;
        $document_reviewer->save();

        if(!empty($request->ver))
        {
            $current_version=DocumentVersion::find($request->ver);
            $document=$current_version->document;
        }else
        {
            $current_version=$document_reviewer->document_version;
            $document=$current_version->document;
        }


        return view('manage.document.reviews.view_document',[
            'document'=>$document,
            'current_version'=>$current_version,
            'document_reviewer'=>$document_reviewer,
        ]);
    }
}
