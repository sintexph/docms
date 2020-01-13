<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\System;
use App\Document;
use App\Section;
use DB;

class ManageSystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('systems.index');
    }

    /**
     * Get the system information
     * @param $id The database id of the system
     */
    public function fetch($id)
    {
        $system=System::find($id);
        abort_if($system==null,404,'System could not be found!');

        return json_encode($system);

    }
    public function list(Request $request)
    {
        $find=$request['find'];

        $systems=System::on();

        if(!empty($find))
        {
            $systems->where(function($condition)use($find){
                $condition->orWhere('name','like','%'.$find.'%')
                ->orWhere('code','like','%'.$find.'%');
            });
        }

        $systems->withTrashed();

        return datatables($systems)->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'code'=>'required|unique:systems',
        ]);

        System::create([
            'name'=>$request['name'],
            'code'=>strtoupper($request['code']),
            'created_by'=>auth()->user()->name
        ]);

        return response()->json(['message'=>'System has been successfully created!']);
    }
    /**
     * Update the system information
     * @param $request Holds the data to be updated
     * @param $id The database id of the system
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required',
            'code'=>'required|unique:systems,code,'.$id,
        ]);



        try {

            DB::beginTransaction();

            $system=System::find($id);
            abort_if($system==null,404,'System could not be found!');


            # get the documents associated with the code
            $documents=Document::where('system_code','=',$system->code)->get();
            # get the sections associated with the code
            $sections=Section::where('system_code','=',$system->code)->get();

            foreach($documents as $document)
            {
                # update
                $document->system_code=$request['code'];
                $document->save();
            }
            foreach($sections as $section)
            {
                # update
                $section->system_code=$request['code'];
                $section->save();
            }


            $system->code=strtoupper($request['code']);
            $system->name=$request['name'];
            $system->edited_by=auth()->user()->name;
            $system->save();


            DB::commit();

            return response()->json(['message'=>'System has been successfully updated!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }

    /**
     * Temporary delete the data and put it into archived state
     * @param $id The database id of the system
     */
    public function archive($id)
    {
        $system=System::find($id);
        abort_if($system==null,404,'System could not be found!');
        
        try {
            DB::beginTransaction();

            $system->edited_by=auth()->user()->name;
            $system->save();

            $system->delete();

            DB::commit();

            return response()->json(['message'=>'System has been successfully put to archived!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(422,$th->getMessage());
        }
    }

    /**
     * Remove the archive state of the data and restore for active usage
     * @param $id The database id of the system
     */
    public function restore($id)
    {
        $system=System::withTrashed()->where('id',$id);
        abort_if($system==null,404,'System could not be found!');

        try {
            DB::beginTransaction();

            $system->restore();
            

            $system=System::find($id);
            $system->edited_by=auth()->user()->name;
            $system->save();

            DB::commit();

            return response()->json(['message'=>'System has been successfully restored!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(422,$th->getMessage());
        }
    }
    /**
     * Permanently delete the data from the system
     * @param $id The database id of the system
     */
    public function delete($id)
    {
        $system=System::withTrashed()->where('id',$id);
        abort_if($system==null,404,'System could not be found!');
        $system->forceDelete();

        return response()->json(['message'=>'System has been successfully deleted!']);
    }
}
