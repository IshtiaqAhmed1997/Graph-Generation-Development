<?php

namespace Database\Factories;

use App\Models\RawRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RawRecordFactory extends Factory
{
    protected $model = RawRecord::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'target_text' => $this->faker->word,
            'accuracy' => $this->faker->numberBetween(0, 100),
            'date_of_service' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'program_name' => $this->faker->randomElement(['Reading', 'Math', 'Science']),
        ];
    }
}
