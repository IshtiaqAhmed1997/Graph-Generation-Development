<?php

namespace App\Imports;

use App\Models\RawRecord;
use App\Services\ErrorLogService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;

class RawRecordImport implements ToCollection
{
    protected $fileUploadId;

    protected $fileType;

    protected $errorLogService;

    protected $existingCombinations = [];

    public function __construct($fileUploadId, $fileType, ErrorLogService $errorLogService)
    {
        $this->fileUploadId = $fileUploadId;
        $this->fileType = $fileType;
        $this->errorLogService = $errorLogService;
    }

    public function collection(Collection $rows)
    {
        $headingSkipped = false;

        foreach ($rows as $row) {
            if (!$headingSkipped) {
                $headingSkipped = true;

                continue;
            }

            $date = isset($row[2]) ? date('Y-m-d', strtotime($row[2])) : null;
            $target = $row[4] ?? null;
            $comboKey = $target . '|' . $date;

            $data = [
                'file_upload_id' => $this->fileUploadId,
                'file_type' => $this->fileType,
                'client_name' => $row[0] ?? null,
                'provider_name' => $row[1] ?? null,
                'date_of_service' => $date,
                'program_name' => $row[3] ?? null,
                'target_text' => $target,
                'raw_data' => $row[5] ?? null,
                'symbolic_data' => $row[6] ?? null,
                'accuracy' => isset($row[7]) && is_numeric($row[7]) ? (int) $row[7] : null,
                'cpt_code' => isset($row[8]) ? trim($row[8]) : null,
                'goal_name' => $this->fileType === 'treatment_plan' ? $row[9] ?? null : null,
                'domain' => $this->fileType === 'treatment_plan' ? $row[10] ?? null : null,
                'mastery_threshold' => $this->fileType === 'treatment_plan' ? $row[11] ?? null : null,
                'session_number' => $row[12] ?? null,
                'notes' => $row[13] ?? null,
                'billable' => filter_var($row[14] ?? true, FILTER_VALIDATE_BOOLEAN),
            ];

            $validator = Validator::make($data, [
                'date_of_service' => 'required|date',
                'target_text' => 'required|string|max:255',
                'accuracy' => 'nullable|integer|min:0|max:100',
                'cpt_code' => ['required', Rule::in(['97153', '97154', '97155', '97156', '0362T'])],
                'billable' => 'boolean',

            ]);
            if (in_array($comboKey, $this->existingCombinations)) {
                $this->errorLogService->log($this->fileUploadId, $this->fileType, $row->toArray(), 'Duplicate goal + date');


                continue;
            }

            if ($validator->fails()) {

                $this->errorLogService->log(
                    $this->fileUploadId,
                    $this->fileType,
                    $row->toArray(),
                    implode('; ', $validator->errors()->all())
                );

                continue;
            }

            RawRecord::create($data);
            $this->existingCombinations[] = $comboKey;
        }
    }
}
