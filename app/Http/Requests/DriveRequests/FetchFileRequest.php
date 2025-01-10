<?php

namespace App\Http\Requests\DriveRequests;

use Illuminate\Foundation\Http\FormRequest;

class FetchFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|string'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Bind the route parameter 'id' into the request data
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
