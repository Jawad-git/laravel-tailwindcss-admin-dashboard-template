<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Room extends Model implements AuditableContract
{
    use Auditable;

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'model');
    }

    public function category()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    protected static function booted()
    {
        static::deleting(function ($room) {
            $room->translations()->delete(); // assuming this is a morphMany
            $room->images()->delete();
        });
    }
}
