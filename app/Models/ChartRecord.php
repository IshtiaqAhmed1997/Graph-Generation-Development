<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_upload_id',
        'goal_name',
        'chart_config',
        'chart_image_path',
        'chart_type',
    ];

    protected $casts = [
        'chart_config' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileUpload()
    {
        return $this->belongsTo(FileUpload::class);
    }

    public function getChartDateRange(): string
    {
        if (! is_array($this->chart_data) || ! isset($this->chart_data['labels'])) {
            return '';
        }

        $dates = $this->chart_data['labels'];
        sort($dates);
        $start = reset($dates);
        $end = end($dates);

        return "$start to $end";
    }
}
