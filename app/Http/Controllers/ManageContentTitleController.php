<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\ContentTitle;
use App\Document;
use App\Section;
use DB;

class ManageContentTitleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('content_titles.index');
    }

    /**
     * Get the system information
     * @param $id The database id of the system
     */
    public function fetch($id)
    {
        $content_title=ContentTitle::find($id);
        abort_if($content_title==null,404,'Content title could not be found!');

        return json_encode($content_title);

    }
    public function list(Request $request)
    {
        $find=$request['find'];

        $content_titles=ContentTitle::on();

        if(!empty($find))
        {
            $content_titles->where(function($condition)use($find){
                $condition->orWhere('name','like','%'.$find.'%')
                ->orWhere('code','like','%'.$find.'%');
            });
        }

        $content_titles->withTrashed();

        return datatables($content_titles)->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'code'=>'required|unique:content_titles'
        ]);

        ContentTitle::create([
            'name'=>$request['name'],
            'code'=>strtoupper($request['code']),
            'created_by'=>auth()->user()->name
        ]);

        return response()->json(['message'=>'Content title has been successfully created!']);
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
            'code'=>'required|unique:content_titles,code,'.$id
        ]);



        try {

            DB::beginTransaction();

            $content_title=ContentTitle::find($id);
            abort_if($content_title==null,404,'Content title could not be found!');


            # get the documents associated with the code
            $documents=Document::where('system_code','=',$content_title->code)->get();
            # get the sections associated with the code
            $sections=Section::where('system_code','=',$content_title->code)->get();

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


            $content_title->code=strtoupper($request['code']);
            $content_title->name=$request['name'];
            $content_title->edited_by=auth()->user()->name;
            $content_title->save();


            DB::commit();

            return response()->json(['message'=>'Content title has been successfully updated!']);

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
        $content_title=ContentTitle::find($id);
        abort_if($content_title==null,404,'Content title could not be found!');
        
        try {
            DB::beginTransaction();

            $content_title->edited_by=auth()->user()->name;
            $content_title->save();

            $content_title->delete();

            DB::commit();

            return response()->json(['message'=>'Content title has been successfully put to archived!']);

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
        $content_title=ContentTitle::withTrashed()->where('id',$id);
        abort_if($content_title==null,404,'Content title could not be found!');

        try {
            DB::beginTransaction();

            $content_title->restore();
            

            $content_title=ContentTitle::find($id);
            $content_title->edited_by=auth()->user()->name;
            $content_title->save();

            DB::commit();

            return response()->json(['message'=>'Content title has been successfully restored!']);

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
        $content_title=ContentTitle::withTrashed()->where('id',$id);
        abort_if($content_title==null,404,'Content title could not be found!');
        $content_title->forceDelete();

        return response()->json(['message'=>'Content title has been successfully deleted!']);
    }
}
