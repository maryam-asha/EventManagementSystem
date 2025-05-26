<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $fillable = [
        'path',
        'filename',
        'mime_type',
        'size',
        'alt_text',
        'is_primary',
        'order',
    ];

    protected $casts = [
        'size' => 'integer',
        'is_primary' => 'boolean',
        'order' => 'integer',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getFullPathAttribute(): string
    {
        return storage_path('app/public/' . $this->path);
    }
}
