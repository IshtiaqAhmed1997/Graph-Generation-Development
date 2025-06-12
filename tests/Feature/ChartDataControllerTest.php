<?php

namespace Tests\Feature;

use App\Models\RawRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChartDataControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_goals_by_accuracy_returns_data()
    {
        $user = User::factory()->create();
        RawRecord::factory()->count(3)->create([
            'user_id' => $user->id,
            'target_text' => 'Reading',
            'accuracy' => 80,
        ]);

        $this->actingAs($user);

        $response = $this->getJson(route('chart.goals'));

        $response->assertStatus(200)
            ->assertJsonStructure(['labels', 'values'])
            ->assertJsonFragment(['labels' => ['Reading']]);
    }

    public function test_behavior_by_date_returns_data()
    {
        $user = User::factory()->create();
        RawRecord::factory()->create([
            'user_id' => $user->id,
            'date_of_service' => now(),
            'accuracy' => 70,
        ]);

        $this->actingAs($user);

        $response = $this->getJson(route('chart.behavior'));
        $response->assertStatus(200)->assertJsonStructure(['labels', 'values']);
    }
}
