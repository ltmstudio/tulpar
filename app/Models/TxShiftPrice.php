<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxShiftPrice extends Model
{
    use HasFactory;

    protected $fillable = ['tx_shift_id', 'tx_level_id', 'tx_car_class_id', 'price'];

    public function shift()
    {
        return $this->belongsTo(TxShift::class, 'tx_shift_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(TxLevel::class, 'tx_level_id', 'id');
    }

    public function carClass()
    {
        return $this->belongsTo(TxCarClass::class, 'tx_car_class_id', 'id');
    }
}
