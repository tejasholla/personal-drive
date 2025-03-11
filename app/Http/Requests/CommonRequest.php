<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CommonRequest extends FormRequest
{
    public static function slugRules(): array
    {
        return ['required', 'string' , self::slugRegex()];
    }

    public static function pathRules(): array
    {
        return ['nullable', 'string', 'regex:/^[ a-zA-Z0-9_\-\/\\\]+$/'];
    }

    public static function passwordRules(): array
    {
        return ['required', 'string', Password::defaults()];
    }

    public static function slugRegex(): string
    {
        return 'regex:/^[a-zA-Z0-9\-\_]{1,20}$/';
    }
}
