<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();            
            $table->string('name');
            $table->string('path')->default('/')->nullable();
            $table->string('bucket')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->tinyInteger('is_dir')->nullable()->default(0);
            $table->string('mime_type')->nullable();
            $table->string('extension')->nullable();
            $table->string('s3_key')->nullable();
            $table->string('s3_url')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
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
