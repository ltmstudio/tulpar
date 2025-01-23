<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class TxDriverProfile extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'phone',
        'name',
        'lastname',
        'avatar',
        'car_name',
        'car_number',
        'people',
        'class_id',
        'delivery',
        'cargo',
        'balance',
        'status',
        'car_image_1',
        'car_image_2',
        'car_image_3',
        'car_image_4',
    ];

    protected $appends = ['class'];

    public function getUserAttribute()
    {
        $usr =  User::where(['role' => 'DRV', 'driver_id' => $this->id])->first();
        if (!$usr) {
            $now = time();
            $usr = new User;
            $usr->email = 'drv' . $this->phone . '@tulpartaxi.system';
            $usr->name = $this->name . ' ' . $this->lastname;
            $usr->phone = $this->phone;
            $usr->password = Hash::make((string) $now);
            $usr->role = 'DRV';
            $usr->driver_id = $this->id;
            $usr->save();
            return $usr;
        } else {
            return $usr;
        }
    }
    public function getClassAttribute()
    {
        if ($this->class_id == null) {
            return null;
        }
        return TxCarClass::find($this->class_id);
    }

    public function getCarImagesAttribute()
    {
        $list = array(
            $this->car_image_1,
            $this->car_image_2,
            $this->car_image_3,
            $this->car_image_4
        );

        return array_filter($list, function ($image) {
            return !is_null($image);
        });
    }

    public function getOperationsAttribute()
    {
        return TxDriverBalanceLog::where('driver_id', $this->id)->orderBy('created_at', 'DESC')->take(30)->get();
    }

    public function getShiftOrdersAttribute()
    {
        return TxShiftOrder::where('driver_id', $this->id)->orderBy('created_at', 'DESC')->take(30)->get();
    }


    public function getLevelAttribute()
    {
        $now = now();
        $oneWeekEarlier = now()->subWeek();
        $count = TxShiftOrder::where('driver_id', $this->id)
            ->whereBetween('created_at', [$oneWeekEarlier, $now])
            ->count();
        $level = TxLevel::where('count', '<=', $count)
            ->orderBy('count', 'desc')
            ->first();
        return $level;
    }
   


    public function syncUser()
    {
        $usr =  User::where(['role' => 'DRV', 'driver_id' => $this->id])->first();
        $now = time();
        if (!$usr) {
            $usr = new User;
            $usr->email = 'drv' . $this->phone . '@tulpar.system';
            $usr->name = $this->name . ' ' . $this->lastname;
            $usr->phone = $this->phone;
            $usr->password = Hash::make((string) $now);
            $usr->role = 'DRV';
            $usr->driver_id = $this->id;
            $usr->save();
        } else {
            $usr->email = 'drv' . $this->phone . '@tulpar.system';
            $usr->phone = $this->phone;
            $usr->name = $this->name . ' ' . $this->lastname;
            $usr->password = Hash::make((string) $now);
            $usr->save();
        }
    }
}
