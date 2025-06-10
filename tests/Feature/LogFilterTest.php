<?php

namespace Tests\Feature;

use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogFilterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Create a test user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_logs_page_loads()
    {
        $response = $this->get(route('logs.index'));
        $response->assertStatus(200);
        $response->assertSee('Upload Logs');
    }

    public function test_filter_by_filename()
    {
        FileUpload::factory()->create(['filename' => 'filter_test_file.xlsx']);
        FileUpload::factory()->create(['filename' => 'other_file.xlsx']);

        $response = $this->get(route('logs.index', ['filename' => 'filter_test_file']));

        $response->assertStatus(200);
        $response->assertSee('filter_test_file.xlsx');
        $response->assertDontSee('other_file.xlsx');
    }

    public function test_filter_by_status()
    {
        FileUpload::factory()->create(['filename' => 'processed_file.xlsx', 'is_processed' => true]);
        FileUpload::factory()->create(['filename' => 'pending_file.xlsx', 'is_processed' => false]);

        $response = $this->get(route('logs.index', ['status' => 'processed']));
        $response->assertStatus(200);
        $response->assertSee('processed_file.xlsx');
        $response->assertDontSee('pending_file.xlsx');
    }

    public function test_filter_by_uploaded_by()
    {
        $userA = User::factory()->create(['name' => 'Uploader A']);
        $userB = User::factory()->create(['name' => 'Uploader B']);

        FileUpload::factory()->create(['user_id' => $userA->id, 'filename' => 'file_by_a.xlsx']);
        FileUpload::factory()->create(['user_id' => $userB->id, 'filename' => 'file_by_b.xlsx']);

        $response = $this->get(route('logs.index', ['uploaded_by' => 'Uploader A']));
        $response->assertStatus(200);
        $response->assertSee('file_by_a.xlsx');
        $response->assertDontSee('file_by_b.xlsx');
    }
}