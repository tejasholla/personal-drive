<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class AdminConfigRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'storage_path' => [
                'required',
                'string',
                'regex:/^[0-9a-z\/\_]+$/'
            ],
        ];
    }
}
