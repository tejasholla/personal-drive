<?php

namespace App\Http\Requests\DriveRequests;

use Illuminate\Foundation\Http\FormRequest;

class FetchFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|string|ulid',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
