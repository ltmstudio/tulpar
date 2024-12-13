<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxCatalogCarModel extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'car_id',
        'name',
        'cyrillic-name',
        'year-from',
        'year-to',
        'class',
    ];

    public function car()
    {
        return $this->belongsTo(TxCatalogCar::class, 'car_id', 'id');
    }
}
