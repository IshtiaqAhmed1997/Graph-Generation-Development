<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoalSession extends Model
{
    protected $fillable = [
        'client_id',
        'goal_id',
        'provider_id',
        'date_of_service',
        'program_name',
        'target_text',
        'raw_data',
        'symbolic_data',
        'accuracy',
        'cpt_code',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
