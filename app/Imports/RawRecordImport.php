<?php

namespace App\Imports;

use App\Http\Requests\RawRecordRowValidation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RawRecordImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];

    public array $validRows = [];

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
                $this->validRows[] = $data;
            }
        }
    }
}
