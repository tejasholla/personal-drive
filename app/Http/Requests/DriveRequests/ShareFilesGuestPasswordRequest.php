<?php

namespace App\Http\Requests\DriveRequests;

use App\Http\Requests\CommonRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ShareFilesGuestPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug' => CommonRequest::slugRules(),
            'password' => ['required', Password::min(6)],
        ];
    }
}
