<?php

namespace App\Models;

use Database\Factories\FileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /** @use HasFactory<FileFactory> */
    use HasFactory;

    protected $guarded = [];

    public static function searchFiles($userId, $searchQuery): File
    {
        return static::where('user_id', $userId)
            ->where('fileName', 'like', $searchQuery . '%')
            ->get();
    }



    public static function deleteOldFiles($id, string $prefix): void
    {
        $query = static::where('bucket_id', $id);
        if ($prefix) {
            $query = $query->where('path', $prefix);
        }
        $res = $query->delete();
    }
}
