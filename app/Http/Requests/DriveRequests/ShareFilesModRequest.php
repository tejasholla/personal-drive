<?php

namespace App\Http\Requests\DriveRequests;

use Illuminate\Foundation\Http\FormRequest;

class ShareFilesModRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
        ];
    }
}
