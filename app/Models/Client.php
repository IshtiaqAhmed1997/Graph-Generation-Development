<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = ['name', 'dob', 'client_code'];

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(GoalSession::class);
    }

    public function goalResults(): HasMany
    {
        return $this->hasMany(GoalResult::class);
    }
}
