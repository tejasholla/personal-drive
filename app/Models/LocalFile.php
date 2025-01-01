<?php

namespace App\Models;

use App\Helpers\FileSizeFormatter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LocalFile extends Model
{
//    protected $guarded = ['private_path', 'user_id'];
    protected $hidden = ['private_path', 'user_id'];

    public static function getByIds(array $fileIds): Collection
    {
        return self::whereIn('id', $fileIds)->get();
    }

    public static function getPrivatePathNameForFileId(int $fileId): string
    {
        $file = self::where('id', $fileId)->first();
        return $file->getPrivatePathNameForFile();
    }

    public function getPrivatePathNameForFile(): string
    {
        return $this->private_path . DIRECTORY_SEPARATOR . $this->filename;
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

    public static function formatFilesSize($fileItems)
    {
        return $fileItems->map(function ($item) {
            if ($item->size) {
                $item->sizeText = FileSizeFormatter::format((int) $item->size);
            }
            return $item;
        });
    }

    public static function searchFiles($userId, $searchQuery)
    {
        $fileItems = static::where('user_id', $userId)
            ->where('filename', 'like', $searchQuery . '%')
            ->get();
        return self::formatFilesSize($fileItems);
    }
}
