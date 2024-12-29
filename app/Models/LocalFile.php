<?php

namespace App\Models;

use App\Helpers\FileSizeFormatter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LocalFile extends Model
{
//    protected $guarded = ['private_path', 'user_id'];
    protected $hidden = ['private_path', 'user_id'];
    public static function getPrivatePath(string $publicPath, string $fileName): string
    {
        return self::where('public_path', $publicPath)->where('filename', $fileName)->value('private_path');
    }

    public static function getPublicPath(string $publicPath): string
    {
        return self::where('public_path', $publicPath)->first()?->private_path ?? '';
    }

    public static function clearTable()
    {
        self::truncate();
    }

    public static function getFilesForPublicPath(string $publicPath): Collection
    {
        $fileItems = self::where('public_path', $publicPath)->get();
        return self::formatFilesSize($fileItems);
    }


    public static function searchFiles($userId, $searchQuery)
    {
        $fileItems = static::where('user_id', $userId)
            ->where('filename', 'like', $searchQuery . '%')
            ->get();
        return self::formatFilesSize($fileItems);
    }

    public static function formatFilesSize($fileItems)
    {
        return $fileItems->map(function ($item) {
            if ($item->size) {
                $item->size = FileSizeFormatter::format((int) $item->size);
            }
            return $item;
        });
    }

}
