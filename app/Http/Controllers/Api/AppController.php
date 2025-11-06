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
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    public function index(Request $request)
    {
        Log::info('index: ' . $request->query('platform'));

        $langs = TxLang::all();
        $cities = TxCity::all();
        $orderTypes = TxRideOrderType::all();
        $carClasses = TxCarClass::all();
        $platform = $request->query('platform');

        // Определяем ключ для версии в зависимости от платформы
        $key = null;
        if ($platform === 'android') {
            $key = 'android_version';
        } elseif ($platform === 'ios') {
            $key = 'ios_version';
        } elseif ($platform === 'web') {
            $key = 'ios_version'; // Используем ios_version для web
        }

        $appVersion = null;
        if ($key) {
            $setting = TxSystemSetting::where('txkey', $key)->first();
            if ($setting) {
                $appVersion = $setting->string_val;
                Log::info('setting: ' . $setting);
            }
        }

        // Возвращаем данные даже если версия не найдена
        return response()->json([
            'success' => true,
            'appVersion' => $appVersion,
            'langs' => $langs,
            'cities' => $cities,
            'orderTypes' => $orderTypes,
            'carClasses' => $carClasses
        ], 200);
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
