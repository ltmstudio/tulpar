<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxDriverModeration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'lastname',
        'birthdate',
        'car_id',
        'car_model_id',
        'car_vin',
        'car_year',
        'car_gos_number',
        'car_image_1',
        'car_image_2',
        'car_image_3',
        'car_image_4',
        'driver_license_number',
        'driver_license_front',
        'driver_license_back',
        'driver_license_date',
        'ts_passport_front',
        'ts_passport_back',
        'status',
        'reject_message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(TxCatalogCar::class, 'car_id', 'id');
    }

    public function carModel()
    {
        return $this->belongsTo(TxCatalogCarModel::class, 'car_model_id', 'id');
    }

    public function getCarImagesAttribute() {
        $list = array(
            $this->car_image_1,
            $this->car_image_2,
            $this->car_image_3,
            $this->car_image_4
        );

        return array_filter($list, function($image) {
            return !is_null($image);
        });
    }
    
    public function getDriverLicenseImagesAttribute() {
        $list = array(
            $this->driver_license_front,
            $this->driver_license_back
        );
        
        return array_filter($list, function($image) {
            return !is_null($image);
        });                                                                                     
    }

    public function getTsPassportImagesAttribute() {
        $list = array(
            $this->ts_passport_front,
            $this->ts_passport_back
        );
        
        return array_filter($list, function($image) {
            return !is_null($image);
        });
    }
}
