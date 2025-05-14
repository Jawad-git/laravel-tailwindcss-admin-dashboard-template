<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;


class Availability extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $fillable = ['start_time', 'end_time', 'weekday'. 'active', 'model_id', 'model_type'];

    public function model()
    {
        return $this->morphTo();
    }
}
