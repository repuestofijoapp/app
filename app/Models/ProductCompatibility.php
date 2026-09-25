<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCompatibility extends Model
{
    protected $table = 'product_compatibilities';

    protected $fillable = [
        'product_id',
        'make_id',
        'car_model_id',
        'engine_id',
        'source',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function make()
    {
        return $this->belongsTo(Make::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function engine()
    {
        return $this->belongsTo(Engine::class, 'engine_id');
    }
}
