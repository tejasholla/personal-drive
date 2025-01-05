<?php

namespace App\Exceptions\PersonalDriveExceptions;

class ImageRelatedException extends PersonalDriveException
{
    public static function invalidImageDriver(): ImageRelatedException
    {
        return new self('Could not generate thumbnail. Missing PHP extension: GD');
    }
}
