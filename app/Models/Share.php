<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

class Share extends Model
{
    protected $appends = ['expiry_time'];
    protected $fillable = ['slug', 'password', 'expiry', 'public_path'];

    public static function add(
        ?string $slug = '',
        ?string $password = '',
        ?string $expiry = '',
        ?string $publicPath = '',
    ): self {
        return static::create([
            'slug' => $slug,
            'password' => $password,
            'expiry' => $expiry,
            'public_path' => $publicPath,
        ]);
    }

    public static function getAll(): Collection
    {
        return static::with(['sharedFiles.localFile:id,filename'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function whereBySlug(string $slug): Builder
    {
        return Share::where('slug', $slug);
    }

    public static function whereById(int $id): Builder
    {
        return Share::where('id', $id);
    }

    public static function getFilenamesBySlug(string $slug): Collection
    {
        return Share::where('slug', $slug)
            ->firstOrFail()
            ->localFiles()
            ->get();
    }

    public function localFiles(): HasManyThrough
    {
        return $this->hasManyThrough(LocalFile::class, SharedFile::class, 'share_id', 'id', 'id', 'file_id');
    }

    public static function getFilenamesByPath(int $shareID, string $path)
    {
        $localFiles = LocalFile::where('public_path', $path)
            ->whereExists(function ($query) use ($shareID) {
                $query->select(DB::raw(1))
                    ->from('local_files AS l')
                    ->join('shared_files AS sf', 'l.id', '=', 'sf.file_id')
                    ->join('shares AS s', 'sf.share_id', '=', 's.id')
                    ->where('s.id', $shareID)
                    ->whereRaw("local_files.public_path LIKE (l.public_path || '/' || l.filename || '%')")
                    ->limit(1);
            })
            ->get();
        return $localFiles;

        /*
         *
            select * from (select l.public_path || '/' || l.filename as base
                           from local_files l
                                    join shared_files sf on l.id = sf.file_id
                                    join shares s on sf.share_id = s.id
                           where s.slug = 'sub1share') f JOIN local_files l2 ON f.base = l2.public_path
         */
        // get public_path + filename of the shared path
    }

    public function getExpiryTimeAttribute(): string
    {
        return $this->created_at->addDays($this->expiry)->format('jS M Y g:i A');
    }

    public function sharedFiles(): HasMany
    {
        return $this->hasMany(SharedFile::class);
    }
}
