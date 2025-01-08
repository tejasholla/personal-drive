<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Share extends Model
{
    protected $appends = ['expiry_time'];
    protected $fillable = ['slug', 'password', 'expiry'];

    public static function add(
        ?string $slug = '',
        ?string $password = '',
        ?string $expiry = '',
    ): self {
        return static::create([
            'slug' => $slug,
            'password' => $password,
            'expiry' => $expiry,
        ]);
    }

    public static function getAll(): Collection
    {
        return static::with(['sharedFiles.localFile:id,filename' ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExpiryTimeAttribute(): string
    {
        return $this->created_at->addDays($this->expiry)->format('jS M Y g:i A');
    }

    public static function whereById(int $id): Builder
    {
        return Share::where('id', $id);
    }

    public function sharedFiles(): HasMany
    {
        return $this->hasMany(SharedFile::class);
    }

    public function localFiles() {
        return $this->hasManyThrough(LocalFile::class, SharedFile::class, 'share_id', 'id', 'id', 'file_id');
    }

    public static function getFilenamesBySlug($slug) {
        return Share::where('slug', $slug)
            ->firstOrFail()
            ->localFiles()
            ->get();
    }

}
