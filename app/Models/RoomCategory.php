<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoomCategory extends Model implements AuditableContract
{
    use Auditable;
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'model');
    }

    public function translated($key, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return $this->translations()
            ->where('language_code', $locale)
            ->where('key', $key)
            ->value('value');
    }

    protected static function booted()
    {
        static::deleting(function ($category) {
            $category->translations()->delete(); // assuming this is a morphMany
        });
    }
}
