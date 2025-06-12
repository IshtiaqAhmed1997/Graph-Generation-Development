<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RawRecordUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_uploads_and_processes_csv_file()
    {
        Storage::fake('local');

        $user = User::factory()->create();

        $csv = <<<'CSV'
client_name,provider_name,date_of_service,program_name,target_text,raw_data,symbolic_data,accuracy,cpt_code
Ali,Dr. Khan,2024-05-01,,Target A,,,85,
CSV;

        $file = UploadedFile::fake()->createWithContent('sample.csv', $csv);

        $response = $this->actingAs($user)->post('/upload', [
            'file' => $file,
        ]);

        $response->assertRedirect('/upload');
        $this->assertDatabaseHas('raw_records', [
            'client_name' => 'Ali',
            'accuracy' => 85,
        ]);
    }
}
