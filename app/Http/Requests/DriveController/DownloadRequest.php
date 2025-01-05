<?php

namespace App\Http\Requests\DriveController;

use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fileList' => 'required',
        ];
    }
}
