<?php

namespace App\Http\Requests\DriveRequests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'files' => 'required|array',
            'files.*' => [
                'required',
                'file',
                function ($attribute, $file, $fail) {
                    if (str_contains($file->getClientOriginalName(), '..')) {
                        $fail('Invalid filename.');
                    }
                }
            ],
            'path' => [
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (str_contains($value, '..') || str_starts_with($value, '/.')) {
                        $fail('Invalid path.');
                    }
                }
            ],
        ];
    }
}
