<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalResult extends Model
{
    protected $fillable = [
        'file_upload_id', 'client_name', 'target_text', 'first_date', 'last_date',
        'total_trials', 'total_correct', 'average_accuracy', 'mastered', 'mastered_on',
    ];
}
