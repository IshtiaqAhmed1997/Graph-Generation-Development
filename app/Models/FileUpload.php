<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FileUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'filepath',
        'file_type',
        'is_processed',
        'validated_by',
        'client_name',
    ];

    public function rawRecords(): HasMany
    {
        return $this->hasMany(RawRecord::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
