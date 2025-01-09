<?php

namespace App\Http\Requests\DriveController;

use Illuminate\Foundation\Http\FormRequest;

class GetThumbnailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|string|alpha_num',
        ];
    }
}
