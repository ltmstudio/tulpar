<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxRideOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'type_id',
        'class_id',
        'user_id',
        'driver_id',
        'user_cost',
        'user_time',
        'people',
        'user_comment',
        'driver_comment',
        'point_a',
        'point_b',
        'geo_a',
        'geo_b',
        'city_a_id',
        'city_b_id',
        'status',
    ];

    public function type()
    {
        return $this->belongsTo(TxRideOrderType::class, 'type_id');
    }

    public function class()
    {
        return $this->belongsTo(TxCarClass::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function cityA()
    {
        return $this->belongsTo(TxCity::class, 'city_a_id');
    }

    public function cityB()
    {
        return $this->belongsTo(TxCity::class, 'city_b_id');
    }
}
