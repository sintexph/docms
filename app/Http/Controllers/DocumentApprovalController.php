<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentApprover;
use App\Helpers\DocumentVersionHelper;
use DB;

class DocumentApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {

        return view('manage.document.approvals.index');
    }

    public function list(Request $request)
    {
        $user=auth()->user();
        $find=$request['find'];
        $state=$request['state'];

        
        
        # Versions to be approved
        # Can see the data only if it was ready for approval and reviewed
        $for_approvals=DocumentApprover::on();
        $for_approvals->join('document_versions','document_approvers.version_id','=','document_versions.id');
        $for_approvals->join('documents','document_versions.document_id','=','documents.id');
        $for_approvals->join('users as vcreator','document_versions.created_by','=','vcreator.id');
        $for_approvals->join('systems','documents.system_code','=','systems.code');
        $for_approvals->join('sections','documents.section_code','=','sections.code');
        $for_approvals->join('categories','documents.category_code','=','categories.code');
        $for_approvals->select([
            'document_versions.document_id',
            'document_approvers.id',
            'documents.version',
            'documents.title',
            'documents.document_number',
            'document_versions.created_at',
            'vcreator.name as version_creator',
            'systems.name as system',
            'sections.name as section',
            'categories.name as category',
            'document_approvers.version_id',
            'document_approvers.approved',
        ])
        ->where('document_versions.for_approval','=',true)
        ->where('document_versions.reviewed','=',true)
        ->where('document_approvers.user_id','=',$user->id);

        switch ($state) {
            case 'rv':
                $for_approvals->where('document_approvers.approved','=',true);
                break;
            case null:
                $for_approvals->where('document_approvers.approved','=',false);
                break;
        }
   
        if(!empty($find))
            $for_approvals->where(function($query)use($find){
                $query->orWhere('documents.title','like','%'.$find.'%')
                ->orWhere('documents.document_number','like','%'.$find.'%')
                ->orWhere('documents.keywords','like','%'.$find.'%');
            });
            
        

        return datatables($for_approvals)->rawColumns(['approved'])->toJson();
    }

    /**
     * Update the document version to approved
     * @param $id The document version approver id
     */
    public function approve($id)
    {
        $user=auth()->user();
        $for_approval=DocumentApprover::find($id);
        
        abort_if($for_approval==null,404,'Could not find the data on the system!');
        $document_version=$for_approval->document_version;
        
        # Must be reviewed first and must be ready for approval
        abort_if($document_version->for_approval!=true || $document_version->reviewed!=true,422,'Document version is not ready for approval!');      

        abort_if($for_approval->approved==true,422,'Document version was been already approved.');      
        # Cannot update if the user is not the approver of the document version
        abort_if($for_approval->user_id!=$user->id,422,'You are not the approver of the document!');

        try {


            DB::beginTransaction();

            DocumentVersionHelper::approve($for_approval);

            DB::commit();

            return response()->json(['message'=>'Document version successfully approved!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort($e->getMessage(),422);
        }

        
        
    }
    
    /**
     * View the document version to be approved
     * @param $id The database id of the document version approver
     */
    public function view_document($id)
    {
        $document_approver=DocumentApprover::find($id);
        abort_if($document_approver==null,404);

        $user=auth()->user();
        # Check if the user is the reviewer of the document version
        abort_if($document_approver==null || $document_approver->user_id!=$user->id,403,'You have no permission to access the page');
        $current_version=$document_approver->document_version;

        # Can be viewed only if they already seen the document
        abort_if($current_version->viewed==false,422,'The document is not ready for approval, please check again later.');

        $document=$current_version->document;
        $current_version_revision=$current_version->revision;
        $references=$document->references;

        $document_approver->viewed=true;
        $document_approver->save();

        return view('manage.document.approvals.view_document',[
            'document'=>$document,
            'current_version'=>$current_version,
            'references'=>$references,
            'current_version_revision'=>$current_version_revision,
            'document_approver'=>$document_approver,
        ]);
    }
}
