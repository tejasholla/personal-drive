<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class EncryptHelper
{
    public static function encrypt(string $text): string
    {
        return Crypt::encryptString($text);
    }
    public static function decrypt(string $text): string
    {
        return Crypt::decryptString($text);
    }
}