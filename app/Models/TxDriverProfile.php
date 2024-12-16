<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class TxDriverProfile extends Model
{
    use HasFactory;

    public $timestamps = false;

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
