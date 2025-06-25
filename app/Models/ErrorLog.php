<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
     protected $fillable = [
        'file_upload_id',
        'source',
        'row_data',
        'error_reason',
    ];
}
