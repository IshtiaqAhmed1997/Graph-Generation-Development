<?php

namespace Tests\Feature;

use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_logs_page_loads()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Upload Logs');
    }

    public function test_filter_by_filename()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        FileUpload::factory()->create([
            'filename' => 'report_abc.xlsx',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('logs.index', ['filename' => 'report_abc']));
        $response->assertStatus(200);
        $response->assertSee('report_abc.xlsx');
    }

    public function test_filter_by_status()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        FileUpload::factory()->create([
            'filename' => 'done.xlsx',
            'is_processed' => true,
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('logs.index', ['status' => 'processed']));
        $response->assertStatus(200);
        $response->assertSee('done.xlsx');
    }

    public function test_filter_by_uploaded_by()
    {
        $user = User::factory()->create(['name' => 'Ali Tester']);
        $this->actingAs($user);

        FileUpload::factory()->create([
            'filename' => 'client_log.xlsx',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('logs.index', ['uploaded_by' => 'Ali']));
        $response->assertStatus(200);
        $response->assertSee('client_log.xlsx');
    }
}
