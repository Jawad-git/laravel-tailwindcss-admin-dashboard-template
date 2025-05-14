<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Translation extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $fillable = ['name', 'description', 'view', '', 'model_id', 'model_type', 'language_id'];

    public function model()
    {
        return $this->morphTo();
    }
}
