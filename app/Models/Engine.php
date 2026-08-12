<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Engine extends Model
{
    protected $fillable = [
        'car_model_id',
        'engine_code',
        'displacement',
        'fuel_type',
        'engine_power'
    ];

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function make()
    {
        return $this->hasOneThrough(Make::class, CarModel::class, 'id', 'id', 'car_model_id', 'make_id');
    }
}
