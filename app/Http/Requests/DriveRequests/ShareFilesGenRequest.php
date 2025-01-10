<?php

namespace App\Http\Requests\DriveRequests;

use Illuminate\Foundation\Http\FormRequest;

class ShareFilesGenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fileList' => 'required|array',
            'slug' => 'nullable|alpha_num|unique:shares,slug',
            'password' => 'nullable|string',
            'expiry' => 'nullable|integer',
        ];
    }
}
