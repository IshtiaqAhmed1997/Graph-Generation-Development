<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_upload_id',
        'client_name',
        'target_text',
        'goal_name',
        'chart_type',
        'chart_config',
        'chart_image_path',
        'version_id'
    ];

    protected $casts = [
        'chart_config' => 'array',
    ];
}
