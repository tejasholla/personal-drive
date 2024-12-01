<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;
    protected $guarded = [];

    public static function getFilesForUserInPath($userId, $path)
    {
        return static::where('user_id', $userId)
            ->where('path', $path)
            ->get();
    }
    public static function searchFiles($userId, $searchQuery)
    {
        return static::where('user_id', $userId)
            ->where('fileName', 'like', $searchQuery.'%')
            ->get();
    }

}
