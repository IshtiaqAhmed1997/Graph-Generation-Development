<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FileUpload extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'filepath',
        'file_type',
        'is_processed',
        'validated_by',
    ];

    public function rawRecords(): HasMany
    {
        return $this->hasMany(RawRecord::class);
    }
}
