<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class SwimmingPool extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    public function translations()
    {
        return $this->morphMany(Translation::class, 'model');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function availabilities()
    {
        return $this->morphMany(Availability::class, 'model');
    }
}
