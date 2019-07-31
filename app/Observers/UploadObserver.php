<?php

namespace App\Observers;

use App\Upload;
use App\Helpers\UploadHelper;

class UploadObserver
{
    public function deleted(Upload $upload)
    {
        UploadHelper::delete_file($upload->file_path);
    }
}
