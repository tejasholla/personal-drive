<?php

namespace App\Exceptions\PersonalDriveExceptions;

class FetchFileException extends PersonalDriveException
{
    public static function notFoundStream(): FetchFileException
    {
        return new self('Could not find file to stream');
    }
    public static function notFoundDownload(): FetchFileException
    {
        return new self('Could not find file to download');
    }
}
