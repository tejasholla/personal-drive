<?php

use App\Models\Bucket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('fileName')->nullable()->index();
            $table->string('path')->nullable();
            $table->tinyInteger('is_dir')->default(0)->nullable();
            $table->string('s3key')->nullable();
            $table->string('s3prefix')->nullable();
            $table->string('s3LastModified')->nullable();
            $table->foreignIdFor(Bucket::class);
            $table->unsignedBigInteger('size')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['path', 'fileName']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
