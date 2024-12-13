<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxCatalogCar extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'cyrillic-name',
        'popular',
        'country',
        'image',
    ];

    public function models()
    {
        return $this->hasMany(TxCatalogCarModel::class, 'car_id', 'id');
    }
}
