<?php

namespace App\Models;

use App\Helpers\FileSizeFormatter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class LocalFile extends Model
{
//    protected $guarded = ['private_path', 'user_id'];
    protected $hidden = ['private_path', 'user_id'];

    public static function getByIds(array $fileIds)
    {
        return self::whereIn('id', $fileIds);
    }

    public static function getPrivatePathNameForFileId(int $fileId): string
    {
        $file = self::where('id', $fileId)->first();
        return $file->getPrivatePathNameForFile();
    }

    public function getPublicPathname(): string
    {
        return $this->public_path . DIRECTORY_SEPARATOR . $this->filename;
    }


    public function getPrivatePathNameForFile(): string
    {
        return $this->private_path . DIRECTORY_SEPARATOR . $this->filename;
    }

    public static function getPrivatePath(string $publicPath): string
    {
        return self::where('public_path', $publicPath)->first()?->private_path ?? '';
    }

    public static function insertRows(array $insertArr)
    {
        return self::upsert($insertArr, ['filename', 'public_path']);
    }

    public static function clearTable()
    {
        self::truncate();
    }

    public static function getFilesForPublicPath(string $publicPath): Collection
    {
        $fileItems = self::where('public_path', $publicPath)->get();
        return self::modifyFileCollection($fileItems);
    }

    private static function modifyFileCollection($fileItems)
    {
        return $fileItems->map(function ($item) {
            if ($item->size) {
                $item->sizeText = FileSizeFormatter::format((int) $item->size);
            }
            if ($item->id) {
                $item->hash = Crypt::encryptString($item->id);
            }
            return $item;
        });
    }

    public static function searchFiles($userId, $searchQuery)
    {
        $fileItems = static::where('user_id', $userId)
            ->where('filename', 'like', $searchQuery . '%')
            ->get();
        return self::modifyFileCollection($fileItems);
    }
}
