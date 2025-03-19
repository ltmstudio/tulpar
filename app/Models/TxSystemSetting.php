<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxSystemSetting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'txkey',
        'string_val',
        'int_val',
        'float_val',
        'json_val',
        'bool_val',
    ];
}
