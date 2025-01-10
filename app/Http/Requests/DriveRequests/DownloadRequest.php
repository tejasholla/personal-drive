<?php

namespace App\Http\Requests\DriveRequests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fileList' => 'required|array',
        ];
    }
}
