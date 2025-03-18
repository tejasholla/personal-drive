<?php

namespace App\Http\Requests\DriveRequests;

use App\Http\Requests\CommonRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateFolderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folderName' => 'required|string|max:255|regex:/^[a-zA-Z0-9_\- ]+$/',
            'path' => CommonRequest::pathRules()
        ];
    }
}
