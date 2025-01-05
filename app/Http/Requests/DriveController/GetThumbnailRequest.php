<?php

namespace App\Http\Requests\DriveController;

use Illuminate\Foundation\Http\FormRequest;

class GetThumbnailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'hashes' => 'required|array|min:1',
            'hashes.*' => 'required|string|alpha_num',
        ];
    }
}
