<?php

namespace App\Http\Requests\AdminRequests;

use Illuminate\Foundation\Http\FormRequest;

class SetupAccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => [ 'required', 'string', 'regex:/^[0-9a-z\/\_]+$/' ],
            'password' => [ 'required', 'string'],
        ];
    }
}
