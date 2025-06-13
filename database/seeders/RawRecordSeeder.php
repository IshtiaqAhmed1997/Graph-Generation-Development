<?php

namespace Database\Seeders;

use App\Models\RawRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RawRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        RawRecord::insert([
            [
                'client_name' => 'Ali Khan',
                'provider_name' => 'John Doe',
                'date_of_service' => '2024-05-01',
                'user_id' => 1,
                'program_name' => 'Program A',
                'target_text' => 'Target A',
                'raw_data' => '5 correct',
                'symbolic_data' => 'Correct',
                'accuracy' => 85,
                'cpt_code' => '97153',
                'billable' => true,
                'file_upload_id' => 10,
                'processed_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_name' => 'Ali Khan',
                'provider_name' => 'John Doe',
                'date_of_service' => '2024-05-02',
                'program_name' => 'Program A',
                'target_text' => 'Target B',
                'raw_data' => '4 correct',
                'symbolic_data' => 'Correct',
                'accuracy' => 92,
                'cpt_code' => '97153',
                'billable' => true,
                'file_upload_id' => 10,
                'processed_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => 1,

            ],
            [
                'client_name' => 'Ali Khan',
                'provider_name' => 'Jane Smith',
                'date_of_service' => '2024-05-03',
                'program_name' => 'Program B',
                'target_text' => 'Target C',
                'raw_data' => '3 incorrect',
                'symbolic_data' => 'Incorrect',
                'accuracy' => 76,
                'cpt_code' => '97155',
                'billable' => true,
                'file_upload_id' => 10,
                'processed_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => 1,

            ],
            [
                'client_name' => 'Ahmed Zubair',
                'provider_name' => 'John Doe',
                'date_of_service' => '2024-05-01',
                'program_name' => 'Program C',
                'target_text' => 'Target A',
                'raw_data' => '6 correct',
                'symbolic_data' => 'Correct',
                'accuracy' => 88,
                'cpt_code' => '97153',
                'billable' => true,
                'file_upload_id' => 10,
                'processed_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => 1,

            ],
            [
                'client_name' => 'Ahmed Zubair',
                'provider_name' => 'Jane Smith',
                'date_of_service' => '2024-05-03',
                'program_name' => 'Program A',
                'target_text' => 'Target B',
                'raw_data' => '2 correct',
                'symbolic_data' => 'Correct',
                'accuracy' => 91,
                'cpt_code' => '97153',
                'billable' => true,
                'file_upload_id' => 10,
                'processed_at' => Carbon::now(),
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => 1,

            ],
        ]);

    }
}
