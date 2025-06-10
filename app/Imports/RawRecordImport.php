<?php

namespace App\Imports;

use App\Http\Requests\RawRecordRowValidation;
use App\Models\RawRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RawRecordImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $data = $row->toArray();

            $validator = Validator::make($data, RawRecordRowValidation::rules());

            if ($validator->fails()) {
                $this->errors[] = [
                    'row' => $index + 2,
                    'messages' => $validator->errors()->all(),
                ];
            } else {
                RawRecord::create([
                    'client_name' => $data['client_name'],
                    'provider_name' => $data['provider_name'],
                    'date_of_service' => $data['date_of_service'],
                    'program_name' => $data['program_name'] ?? null,
                    'target_text' => $data['target_text'],
                    'raw_data' => $data['raw_data'] ?? null,
                    'symbolic_data' => $data['symbolic_data'] ?? null,
                    'accuracy' => $data['accuracy'] ?? null,
                    'cpt_code' => $data['cpt_code'] ?? null,
                ]);
            }
        }
    }
}
