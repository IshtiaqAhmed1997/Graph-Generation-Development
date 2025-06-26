<?php

namespace App\Services;

use App\Models\ErrorLog;

class ErrorLogService
{
    /**
     * Store an error entry in the error_logs table.
     *
     * @param  string  $source  e.g., 'treatment_plan' or 'medical_record'
     * @param  array  $rowData  The row data that failed
     * @param  string  $errorReason  Why the row failed
     */
    public function log(?int $fileUploadId, string $source, array $rowData, string $errorReason): void
    {
        ErrorLog::create([
            'file_upload_id' => $fileUploadId,
            'source' => $source,
            'row_data' => json_encode($rowData),
            'error_reason' => $errorReason,
        ]);
    }
}
