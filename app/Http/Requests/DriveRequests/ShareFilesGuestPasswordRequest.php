<?php

namespace App\Http\Requests\DriveRequests;

use Illuminate\Foundation\Http\FormRequest;

class ShareFilesGuestPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug' => 'required|string|alpha_num',
            'password' => 'required|string',
        ];
    }
}
