<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TxCarClass;
use App\Models\TxCity;
use App\Models\TxLang;
use App\Models\TxRideOrderType;
use App\Models\TxSystemSetting;
use App\Models\TxTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function index(Request $request)
    {
        $langs = TxLang::all();
        $cities = TxCity::all();
        $orderTypes = TxRideOrderType::all();
        $carClasses = TxCarClass::all();
        $platform = $request->query('platform');
        $key = $platform === 'android' ? 'android_version' : ($platform === 'ios' ? 'ios_version' : null);

        if ($key) {
            $setting = TxSystemSetting::where('txkey', $key)->first();

            if ($setting) {
                return response()->json([
                    'success' => true,
                    'appVersion' => $setting->string_val,
                    'langs' => $langs,
                    'cities' => $cities,
                    'orderTypes' => $orderTypes,
                    'carClasses' => $carClasses
                ], 200);
            }
        }

        return response()->json(['success' => false, 'message' => 'Configuration not found'], 404);
    }

    public function localization()
    {
        $langs  = TxLang::all();

        $result = array();
        foreach ($langs  as $lang) {
            $result[$lang->code] = array();
            $values = TxTranslation::where('tx_lang_id', $lang->id)->get();
            foreach ($values as $value) {
                $result[$lang->code][$value->key] = $value->value;
            }
        }

        return response()->json($result);
    }

    public function cities()
    {
        $cities = TxCity::all();
        return response()->json($cities);
    }

    public function orderTypes()
    {
        $orderTypes = TxRideOrderType::all();
        return response()->json($orderTypes);
    }
}
