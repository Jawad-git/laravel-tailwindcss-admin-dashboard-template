<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomCategory extends Model
{
    use Auditable;
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

}
