<?php

namespace App\Exceptions\PersonalDriveExceptions;

class ThumbnailException extends PersonalDriveException
{
    public static function noffmpeg(): ThumbnailException
    {
        return new self('FFMpeg not found ! Please install video thumbnails');
    }
}
