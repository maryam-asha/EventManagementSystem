<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasImages
{
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getPrimaryImageAttribute(): ?Image
    {
        return $this->images()->where('is_primary', true)->first();
    }

    public function getImagesAttribute()
    {
        return $this->images()->orderBy('order')->get();
    }

    public function getImageCountAttribute(): int
    {
        return $this->images()->count();
    }

    public function hasImages(): bool
    {
        return $this->image_count > 0;
    }
} 