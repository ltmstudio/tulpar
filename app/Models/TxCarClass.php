<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxCarClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'cost',
        'priority',
    ];
}
