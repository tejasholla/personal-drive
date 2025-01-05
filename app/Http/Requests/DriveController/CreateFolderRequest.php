<?php

namespace App\Http\Requests\DriveController;

use Illuminate\Foundation\Http\FormRequest;

class CreateFolderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folderName' => 'required|string|max:255',
            'path' => 'max:255',
        ];
    }
}
