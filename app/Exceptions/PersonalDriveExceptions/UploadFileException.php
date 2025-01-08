<?php

namespace App\Exceptions\PersonalDriveExceptions;

class UploadFileException extends PersonalDriveException
{
    public static function outofmemory(): UploadFileException
    {
        return new self('Memory exhausted while uploading. Increase PHP allocated memory');
    }
    public static function nonewdir(): UploadFileException
    {
        return new self('Could not create new directory');
    }
}
