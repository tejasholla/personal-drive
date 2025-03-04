<?php

namespace App\Http\Requests\DriveRequests;

use App\Http\Requests\CommonRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ShareFilesGenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fileList' => 'required|array',
            'slug' =>  array_merge(CommonRequest::slugRules(), ['unique:shares']),
            'password' => ['required', Password::defaults(), 'confirmed'],
            'expiry' => 'nullable|integer',
        ];
    }
}
