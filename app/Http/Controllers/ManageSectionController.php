<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Section;
use App\Document;
use DB;

class ManageSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('sections.index');
    }

    /**
     * Get the section information
     * @param $id The database id of the section
     */
    public function fetch($id)
    {
        $section=Section::find($id);
        abort_if($section==null,404,'Section could not be found!');

        return json_encode($section);

    }
    public function list(Request $request)
    {
        $find=$request['find'];

        $sections=Section::on();
        $sections->with('system');

        if(!empty($find))
        {
            $sections->where(function($condition)use($find){
                $condition->orWhere('name','like','%'.$find.'%')
                ->orWhere('system_code','like','%'.$find.'%')
                ->orWhere('code','like','%'.$find.'%');
            });
        }

        $sections->withTrashed();

        return datatables($sections)->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'system_code'=>'required',
            'code'=>'required|unique:sections',
        ]);

        Section::create([
            'name'=>$request['name'],
            'system_code'=>$request['system_code'],
            'code'=>strtoupper($request['code']),
            'created_by'=>auth()->user()->name
        ]);

        return response()->json(['message'=>'Section has been successfully created!']);
    }
    /**
     * Update the section information
     * @param $request Holds the data to be updated
     * @param $id The database id of the section
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required',
            'system_code'=>'required',
            'code'=>'required|unique:sections,code,'.$id,
        ]);



        try {

            DB::beginTransaction();

            $section=Section::find($id);
            abort_if($section==null,404,'Section could not be found!');

            # get the documents associated with the code
            $documents=Document::where('section_code','=',$section->code)->get();

            foreach($documents as $document)
            {
                # update
                $document->section_code=$request['code'];
                $document->save();
            }

            $section->code=strtoupper($request['code']);
            $section->name=$request['name'];
            $section->system_code=$request['system_code'];
            $section->edited_by=auth()->user()->name;
            $section->save();

            DB::commit();
            

            return response()->json(['message'=>'Section has been successfully updated!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }

    /**
     * Temporary delete the data and put it into archived state
     * @param $id The database id of the section
     */
    public function archive($id)
    {
        $section=Section::find($id);
        abort_if($section==null,404,'Section could not be found!');
        
        try {
            DB::beginTransaction();

            $section->edited_by=auth()->user()->name;
            $section->save();

            $section->delete();

            DB::commit();

            return response()->json(['message'=>'Section has been successfully put to archived!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(422,$th->getMessage());
        }
    }

    /**
     * Remove the archive state of the data and restore for active usage
     * @param $id The database id of the section
     */
    public function restore($id)
    {
        $section=Section::withTrashed()->where('id',$id);
        abort_if($section==null,404,'Section could not be found!');

        try {
            DB::beginTransaction();

            $section->restore();
            

            $section=Section::find($id);
            $section->edited_by=auth()->user()->name;
            $section->save();

            DB::commit();

            return response()->json(['message'=>'Section has been successfully restored!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(422,$th->getMessage());
        }
    }
    /**
     * Permanently delete the data from the section
     * @param $id The database id of the section
     */
    public function delete($id)
    {
        $section=Section::withTrashed()->where('id',$id);
        abort_if($section==null,404,'Section could not be found!');
        $section->forceDelete();

        return response()->json(['message'=>'Section has been successfully deleted!']);
    }
}
