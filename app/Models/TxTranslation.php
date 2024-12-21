<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'key',
        'tx_lang_id',
        'value',
        'module'
    ];
}
