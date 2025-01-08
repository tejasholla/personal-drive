<?php

namespace App\Http\Requests\DriveController;

use Illuminate\Foundation\Http\FormRequest;

class ShareFilesGuestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug' => 'required|string|alpha_num',
        ];
    }


    protected function prepareForValidation(): void
    {
        // Bind the route parameter 'hash' into the request data
        $this->merge([
            'slug' => $this->route('slug'),
        ]);
    }
}