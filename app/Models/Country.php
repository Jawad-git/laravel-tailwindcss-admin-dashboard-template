<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Country extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    public function flag()
    {
        return $this->hasOne(FlagIcon::class, 'title', 'iso2');
    }
}
