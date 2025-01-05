<?php

namespace App\Http\Requests\DriveController;

use Illuminate\Foundation\Http\FormRequest;

class FetchFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'hash' => 'required|string'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Bind the route parameter 'hash' into the request data
        $this->merge([
            'hash' => $this->route('hash'),
        ]);
    }
}
