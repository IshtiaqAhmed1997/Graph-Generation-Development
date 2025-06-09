<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'goal_result_id', 'user_id', 'action', 'details', 'version_id',
    ];

    public function goalResult(): BelongsTo
    {
        return $this->belongsTo(GoalResult::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
