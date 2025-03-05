<?php

namespace App\Http\Requests\AdminRequests;

use App\Http\Requests\CommonRequest;
use Illuminate\Foundation\Http\FormRequest;

class AdminConfigRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'storage_path' => CommonRequest::pathRules(),
        ];
    }
}
