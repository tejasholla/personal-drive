<?php

namespace App\Exceptions\PersonalDriveExceptions;

class ShareFileException extends PersonalDriveException
{
    public static function couldNotShare(): ShareFileException
    {
        return new self('No valid files to share. Database issue ? Try a Resync');
    }
}
