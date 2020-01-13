<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Category;
use App\Document;
use DB;

class ManageCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('categories.index');
    }

    /**
     * Get the category information
     * @param $id The database id of the category
     */
    public function fetch($id)
    {
        $category=Category::find($id);
        abort_if($category==null,404,'Category could not be found!');

        return json_encode($category);

    }
    public function list(Request $request)
    {
        $find=$request['find'];

        $categories=Category::on();

        if(!empty($find))
        {
            $categories->where(function($condition)use($find){
                $condition->orWhere('name','like','%'.$find.'%')
                ->orWhere('code','like','%'.$find.'%');
            });
        }

        $categories->withTrashed();

        return datatables($categories)->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'code'=>'required|unique:categories',
        ]);

        Category::create([
            'name'=>$request['name'],
            'code'=>strtoupper($request['code']),
            'created_by'=>auth()->user()->name
        ]);

        return response()->json(['message'=>'Category has been successfully created!']);
    }
    /**
     * Update the category information
     * @param $request Holds the data to be updated
     * @param $id The database id of the category
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required',
            'code'=>'required|unique:categories,code,'.$id,
        ]);



        try {

            DB::beginTransaction();

            $category=Category::find($id);
            abort_if($category==null,404,'Category could not be found!');

            # get the documents associated with the code
            $documents=Document::where('category_code','=',$category->code)->get();

            foreach($documents as $document)
            {
                # update
                $document->category_code=$request['code'];
                $document->save();
            }


            $category->code=strtoupper($request['code']);
            $category->name=$request['name'];
            $category->edited_by=auth()->user()->name;
            $category->save();

            DB::commit();

            return response()->json(['message'=>'Category has been successfully updated!']);

        } catch (\Exception $e) {
            DB::rollBack();
            abort(422,$e->getMessage());
        }
    }
    /**
     * Temporary delete the data and put it into archived state
     * @param $id The database id of the category
     */
    public function archive($id)
    {
        $category=Category::find($id);
        abort_if($category==null,404,'Category could not be found!');
        
        try {
            DB::beginTransaction();

            $category->edited_by=auth()->user()->name;
            $category->save();

            $category->delete();

            DB::commit();

            return response()->json(['message'=>'Category has been successfully put to archived!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(422,$th->getMessage());
        }
    }

    /**
     * Remove the archive state of the data and restore for active usage
     * @param $id The database id of the category
     */
    public function restore($id)
    {
        $category=Category::withTrashed()->where('id',$id);
        abort_if($category==null,404,'Category could not be found!');

        try {
            DB::beginTransaction();

            $category->restore();
            

            $category=Category::find($id);
            $category->edited_by=auth()->user()->name;
            $category->save();

            DB::commit();

            return response()->json(['message'=>'Category has been successfully restored!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(422,$th->getMessage());
        }
    }
    /**
     * Permanently delete the data from the category
     * @param $id The database id of the category
     */
    public function delete($id)
    {
        $category=Category::withTrashed()->where('id',$id);
        abort_if($category==null,404,'Category could not be found!');
        $category->forceDelete();

        return response()->json(['message'=>'Category has been successfully deleted!']);
    }
}
