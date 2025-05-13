<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Food extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'model');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    protected static function booted()
    {
        static::deleting(function ($food) {
            $food->translations()->delete(); // assuming this is a morphMany
            $food->images()->delete();
        });
    }
}
