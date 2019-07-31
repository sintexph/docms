<?php

namespace App\Helpers;
use App\Upload;
use File;
use App\User;
class UploadHelper 
{

    /**
     * Upload file to the disk storage
     * @param $file The file to be uploaded
     * @param $user The user who will upload the file
     */
    public static function upload_file($file,User $user)
    {

        $storage=self::storage();
        $filename=$file->getClientOriginalName();
        
        # Create first a data upload on the database
        $upload= new Upload;
        $upload->file_name=$filename;
        $upload->file_path='temporary';
        $upload->file_type='Document';
        $upload->file_mime='temporary_mime';
        $upload->file_size=$file->getClientSize();
        $upload->uploaded_by=$user->name;
        $upload->save();

        # Formulate the the file store name
        $store_name=$upload->id.'_'.strtolower(\Carbon\Carbon::now()->format('Ymd_H_i_s').'_'.$filename);
        $store_name=str_replace(' ','_',$store_name);
        
        # Upload the file to the storage
        $uploaded_file=$storage->putFileAs('uploads/files', $file,$store_name);
        $mime=$storage->mimeType($uploaded_file);

        # Change the path on the database data
        $upload->file_path=$uploaded_file;
        $upload->file_mime=$mime;
         # Set the preview flag only for pdf
        if (strpos(strtolower($mime), 'pdf') !== false) {
            $upload->has_preview=true;
        }
        $upload->save();

        
        return $upload;
    }

    public static function storage()
    {
        return \Storage::disk('sci-ftp');
    }

    /**
     * Delete the file from storage
     */
    public static function delete_file($path)
    {
        return self::storage()->delete($path);
    }


}
