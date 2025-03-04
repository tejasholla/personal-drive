<?php

namespace App\Http\Requests\DriveRequests;

use App\Http\Requests\CommonRequest;
use Illuminate\Foundation\Http\FormRequest;

class ShareFilesGuestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug' => CommonRequest::slugRules(),
            'path' => CommonRequest::pathRules()        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => CommonRequest::slugRules(),
            'path' => CommonRequest::pathRules()        ]);
    }
}
