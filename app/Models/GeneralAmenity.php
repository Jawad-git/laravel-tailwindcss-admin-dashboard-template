<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class GeneralAmenity extends Model implements AuditableContract
{
    use Auditable;
    public function roomCategories(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, "room_amenity");
    }
}
