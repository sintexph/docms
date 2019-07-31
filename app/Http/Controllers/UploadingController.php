<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Helpers\UploadHelper;

class UploadingController extends Controller
{
    /**
     * Upload image to the server
     */
    public function image(Request $request)
    {
        $this->validate($request,['file'=>'required|file|mimes:png,jpeg,jpg,max:2000']);
        $file=$request['file'];
        $user=auth()->user();

        try {

            DB::beginTransaction();
            
            $upload=UploadHelper::upload_file($file,$user);

            DB::commit();

            return response()->json([
                'message'=>'File(s) has been successfully uploaded!',
                'upload_id'=>$upload->id,
            ]);

        } catch (\Exception $ex) {
            DB::rollBack();
            abort(404,$ex->getMessage());
        }
    }
}
