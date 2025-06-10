<?php

namespace Database\Factories;

use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileUploadFactory extends Factory
{
    protected $model = FileUpload::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'filename' => $this->faker->word . '.xlsx',
            'filepath' => 'uploads/' . $this->faker->uuid . '.xlsx',
            'file_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'is_processed' => $this->faker->boolean,
            'validated_by' => null,
        ];
    }
}