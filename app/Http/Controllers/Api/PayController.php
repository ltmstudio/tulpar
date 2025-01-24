<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TxSystemSetting;
use Illuminate\Http\Request;

class PayController extends Controller
{
    public function getPayInfo(){
        $pay_link = TxSystemSetting::where('txkey', 'tupar_pay_link')->first();
        $pay_qr_image = TxSystemSetting::where('txkey', 'tupar_pay_qr_image')->first();
        
        if(!$pay_link || !$pay_qr_image){
            return response()->json([
                'error' => 'Pay info not found'
            ], 404);
        }
        
        return response()->json([
            'pay_link' => $pay_link->string_val,
            'pay_qr_image' => $pay_qr_image->string_val
        ]);
    }
}
