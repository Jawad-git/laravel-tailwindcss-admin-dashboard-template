<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class SocialMediasActive extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $fillable = ['name', 'value', 'language'];
}
