<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $fillable = ['make_id', 'name', 'version_no', 'start_year', 'end_year', 'image'];


    public function make()
    {
        return $this->belongsTo(Make::class);
    }

    public function engines()
    {
        return $this->hasMany(Engine::class);
    }
}
