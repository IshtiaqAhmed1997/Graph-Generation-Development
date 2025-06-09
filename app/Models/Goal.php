<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    protected $fillable = ['client_id', 'name', 'mastery_criteria', 'description'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function goalSessions(): HasMany
    {
        return $this->hasMany(GoalSession::class);
    }

    public function goalResults(): HasMany
    {
        return $this->hasMany(GoalResult::class);
    }
}
