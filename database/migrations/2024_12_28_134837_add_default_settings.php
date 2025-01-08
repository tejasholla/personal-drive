<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'storage_path', 'value' => '/opt/personaldrive/storage', 'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'uuidForStorageFiles',
                'value' => 'storage_' . Str::uuid()->toString(), 'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'uuidForThumbnails', 'value' => 'thumbnail_' . Str::uuid()->toString(), 'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
