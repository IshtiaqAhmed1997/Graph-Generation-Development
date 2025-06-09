<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoalResult extends Model
{
    protected $fillable = [
        'client_id', 'goal_id', 'file_upload_id',
        'baseline_date', 'baseline_accuracy', 'latest_accuracy',
        'mastered', 'chart_path', 'report_path', 'was_duplicate',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function goalSessions(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function fileUpload(): BelongsTo
    {
        return $this->belongsTo(FileUpload::class);
    }
}
