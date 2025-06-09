<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    protected $fillable = ['name', 'email', 'license_number'];

    public function goalSessions(): HasMany
    {
        return $this->hasMany(GoalSession::class);
    }
}
