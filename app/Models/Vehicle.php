<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate',
        'vin',
        'engine_code',
        'engine_no',
        'brand',
        'model',
        'year',
        'manufacturing_year',
        'body_type',
        'color',
        'version_no',
        'serie_no',
        'engine_power',
        'fuel_type',
        'cylinders',
        'displacement',
        'weight_dry',
        'weight_net',
        'payload',
        'weight_gross',
        'seats',
        'length',
        'width',
        'height',
        'wheel_formula',
        'passengers',
        'doors',
        'wheels',
        'axles',
        'usage_type',
        'category_code',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

// Relationships can be added here as needed
}
