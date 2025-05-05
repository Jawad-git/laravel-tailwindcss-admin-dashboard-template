<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class RoomImage extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
