<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . '.' . $this->faker->fileExtension(),
            'path' => $this->faker->filePath(),
            'bucket' => $this->faker->word() . '_bucket',
            'size' => $this->faker->numberBetween(1024, 1048576), // size in bytes (1KB to 1MB)
            'mime_type' => $this->faker->mimeType(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
