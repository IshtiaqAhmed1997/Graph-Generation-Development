<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawRecord extends Model
{
    protected $fillable = [
        'file_upload_id', 'client_name', 'provider_name', 'date_of_service',
        'program_name', 'target_text', 'raw_data', 'symbolic_data',
        'accuracy', 'cpt_code', 'billable', 'processed_at',
    ];

    public function fileUpload(): BelongsTo
    {
        return $this->belongsTo(FileUpload::class);
    }
}
