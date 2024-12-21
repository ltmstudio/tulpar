<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxDriverBalanceLog extends Model
{
    use HasFactory;

    public function shift_order()
    {
        return $this->hasOne(TxShiftOrder::class, 'id', 'shift_order_id');
    }
}
