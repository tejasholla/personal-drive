<?php

namespace App\Models;

use App\Helpers\EncryptHelper;
use App\Helpers\FileSizeFormatter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LocalFile extends Model
{
    protected $hidden = ['private_path', 'user_id'];
    protected $fillable = ['filename', 'is_dir', 'public_path', 'private_path', 'size', 'user_id', 'file_type'];
    public $timestamps = true;

    public function sharedFiles(): HasMany
    {
        return $this->hasMany(SharedFile::class, 'file_id');
    }

    public static function getByIds(array $fileIds): Builder
    {
        return self::whereIn('id', $fileIds);
    }


    public function getPublicPathname(): string
    {
        return $this->public_path . DIRECTORY_SEPARATOR . $this->filename;
    }


    public function getPrivatePathNameForFile(): string
    {
        return $this->private_path . DIRECTORY_SEPARATOR . $this->filename;
    }

    public static function insertRows(array $insertArr): int
    {
        return self::upsert($insertArr, ['filename', 'public_path']);
    }

    public static function clearTable(): void
    {
        self::truncate();
    }

    public static function getFilesForPublicPath(string $publicPath): Collection
    {
        $fileItems = self::where('public_path', $publicPath)
            ->orderBy('created_at', 'desc')
            ->get();
        return self::modifyFileCollection($fileItems);
    }

    private static function modifyFileCollection(Collection $fileItems): Collection
    {
        return $fileItems->map(function ($item) {
            if ($item->size) {
                $item->sizeText = FileSizeFormatter::format((int) $item->size);
            }
            if ($item->id) {
                $item->hash = EncryptHelper::encrypt($item->id);
            }
            return $item;
        });
    }

    public static function searchFiles(string $searchQuery): Collection
    {
        $fileItems = static::where('filename', 'like', $searchQuery . '%')->get();
        return self::modifyFileCollection($fileItems);
    }
}
