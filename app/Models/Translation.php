<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = ['name', 'model_id', 'model_type', 'language_id'];

    public function model()
    {
        return $this->morphTo();
    }
}
