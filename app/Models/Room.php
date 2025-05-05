<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Room extends Model implements AuditableContract
{
    use Auditable;

    public function roomImages()
    {
        return $this->hasMany(RoomImage::class, 'room_id');
    }

    public function category()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }
}
