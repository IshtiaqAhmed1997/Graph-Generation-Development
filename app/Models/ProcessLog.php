<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessLog extends Model
{
   protected $fillable = [
        'upload_id',
        'target_text',
        'records_processed',
        'final_accuracy',
    ];
}
