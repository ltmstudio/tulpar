<?php

namespace Database\Seeders;

use App\Models\TxSystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TxSystemSetting::updateOrCreate(['txkey' => 'android_version'], ['string_val' => '1.0.0']);
        TxSystemSetting::updateOrCreate(['txkey' => 'ios_version'], ['string_val' => '1.0.0']);
        TxSystemSetting::updateOrCreate(['txkey' => 'tupar_pay_link'], ['string_val' => 'https://google.com']);
        TxSystemSetting::updateOrCreate(['txkey' => 'tupar_pay_qr_image'], ['string_val' => 'http://192.168.31.59/storage/pay/tulpar_pay.png']);
    }
}
