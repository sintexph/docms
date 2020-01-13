<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use DB;
use App\Helpers\DocumentActionHistoryHelper;


class DocumentAdminActionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Archive the document
     * @param $id The database id of the document
     */
    public function archive_document($id)
    {
        $document=Document::find($id);
        abort_if($document==null,404);
        
        if($document->archived==true)
            return response()->json(['message'=>'Document has been successfully added to archived already!']);


        try {
            DB::beginTransaction();

            $document->archived=true;
            $document->archived_by=auth()->user()->name;
            $document->archived_date=\Carbon\Carbon::now();
            $document->save();
            
            DocumentActionHistoryHelper::archive_document($document,auth()->user());

            DB::commit();
            return response()->json(['message'=>'Document has been successfully put to archived!']);

            
        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }


        
    }


    /**
     * Remove the document from archive
     * @param $id The database id of the document
     */
    public function put_trash($id)
    {
        $document=Document::find($id);
        abort_if($document==null,404);
        
        try {
            DB::beginTransaction();
            DocumentActionHistoryHelper::put_document_to_trash($document,auth()->user());
            $document->delete();

            DB::commit();
            return response()->json(['message'=>'Document has been successfully put to trash!']);

            
        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }

    /**
     * Remove the document from archive
     * @param $id The database id of the document
     */
    public function remove_archive_document($id)
    {
        $document=Document::find($id);
        abort_if($document==null,404);
        
        try {
            DB::beginTransaction();
            $document->archived=false;
            $document->archived_by=null;
            $document->archived_date=null;
            $document->save();
            
            DocumentActionHistoryHelper::remove_archive_document($document,auth()->user());

            DB::commit();
            return response()->json(['message'=>'Document has been successfully removed from archived!']);

            
        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }

    /**
     * Change the status of the document
     * @param $request Holds the status
     * @param $id The database id of the document
     */
    public function change_status(Request $request,$id)
    {
        $this->validate($request,[
            'status'=>'required'
        ]);

        try {
            
            $document=Document::find($id);
            if($document==null)
                return response()->json(['message'=>'Document could not be found on the system!'],404);

            $user=auth()->user();

            DB::beginTransaction();

            $status=$request['status'];

            switch ($status) {
                case 'obsolete':
                    $document->obsolete=true;
                    DocumentActionHistoryHelper::change_status($document,$user,'OBSOLETE');
                    break;
                case 'active':
                    $document->obsolete=false;
                    DocumentActionHistoryHelper::change_status($document,$user,'ACTIVE');
                    break;
            }
            $document->save();

            DB::commit();

            
            return response()->json(['message'=>'Document status has successfully updated!']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }

    }

    /**
     * Lock the document
     * @param $id The database id of the document
     */
    public function lock_document($id)
    {
        try {
            $document=Document::find($id);
            \abort_if($document==null,404);

            $user=auth()->user();

            DB::beginTransaction();

            $document->locked=true;
            $document->save();

            # Save action history
            DocumentActionHistoryHelper::lock_document($document,$user);

            DB::commit();

            return response()->json(['message'=>'Document has been successfully locked!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }

    /**
     * Unlock the document
     */
    public function unlock_document($id)
    {
        try {
            $document=Document::find($id);
            \abort_if($document==null,404);

            $user=auth()->user();

            DB::beginTransaction();

            $document->locked=false;
            $document->save();

            # Save action history
            DocumentActionHistoryHelper::unlock_document($document,$user);

            DB::commit();

            return response()->json(['message'=>'Document has been successfully unlocked!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }
}
