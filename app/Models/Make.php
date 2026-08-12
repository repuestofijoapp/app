<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    protected $fillable = ['name'];

    public function carModels()
    {
        return $this->hasMany(CarModel::class);
    }
}
