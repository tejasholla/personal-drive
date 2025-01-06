<?php

namespace App\Http\Requests\DriveController;

use Illuminate\Foundation\Http\FormRequest;

class FileDeleteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fileList' => 'required|array',
        ];
    }
}
