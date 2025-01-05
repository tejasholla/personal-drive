<?php

namespace App\Http\Requests\DriveController;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fileList' => 'required|array',
            'fileList.*' => 'required|integer|in:0,1',
        ];
    }
}
