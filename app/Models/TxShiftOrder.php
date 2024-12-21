<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxShiftOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'class_id',
        'hours',
        'hours_state',
        'level_name',
        'price',
    ];

    public function driver()
    {
        return $this->belongsTo(TxDriverProfile::class, 'driver_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(TxCarClass::class, 'class_id', 'id');
    }
}
