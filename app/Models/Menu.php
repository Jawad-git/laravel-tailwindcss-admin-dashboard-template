<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Menu extends Model implements AuditableContract
{
    use HasFactory, Auditable;



    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'model');
    }
    public function images()
    {
        return $this->morphOne(Image::class, 'model');
    }

    protected static function booted()
    {
        static::deleting(function ($menu) {
            $menu->translations()->delete(); // assuming this is a morphMany
            $menu->images()->delete();
        });
    }
}
