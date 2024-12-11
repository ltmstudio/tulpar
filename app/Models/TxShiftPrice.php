<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxShiftPrice extends Model
{
    use HasFactory;

    protected $fillable = ['tx_shift_id', 'tx_level_id', 'tx_car_class_id', 'price'];
}
