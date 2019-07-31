<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentDraft;
use Illuminate\Support\Facades\Input;

class DocumentDraftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('drafts.index');
    }

     public function list(Request $request)
    {
        $find=$request['find'];
        $drafts=DocumentDraft::where('user_id','=',auth()->user()->id);
        if(!empty($find))
        {
            $drafts->where(function($condition)use($find){
                $condition->orWhere('document_title','like','%'.$find.'%');
            });
        }

        return datatables($drafts)->toJson();
    }

    
    
    public function delete($id)
    {
        $draft=DocumentDraft::find($id);
        abort_if($draft==null,404,'Draft could not be found!');
        $draft->delete();

        return response()->json(['message'=>'Draft has been successfully deleted!']);
    }
    
}
