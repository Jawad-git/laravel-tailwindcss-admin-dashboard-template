<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Amenity extends Model implements AuditableContract
{
    use Auditable;
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class);
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'model');
    }

    protected static function booted()
    {
        static::deleting(function ($amenity) {
            $amenity->translations()->delete(); // assuming this is a morphMany
        });
    }
}
