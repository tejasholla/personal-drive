<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class UploadFileHelper
{

    public static function getUploadedFileFullPath(UploadedFile $file): string
    {
        $fileName = $file->getClientOriginalName();
        $fileIndex = array_search($file->getClientOriginalName(), $_FILES['files']['name']);
        if ($fileIndex !== false) {
            $fileName = $_FILES['files']['full_path'][$fileIndex];
        }
        return  $fileName;
    }

}