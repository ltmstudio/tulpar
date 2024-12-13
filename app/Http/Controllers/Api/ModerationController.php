<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TxDriverModeration;

class ModerationController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'nullable|string',
            'lastname' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'car_id' => 'nullable|exists:tx_catalog_cars,id',
            'car_model_id' => 'nullable|exists:tx_catalog_car_models,id',
            'car_vin' => 'nullable|string',
            'car_year' => 'nullable|string',
            'car_gos_number' => 'nullable|string',
            'car_image_1' => 'nullable|string',
            'car_image_2' => 'nullable|string',
            'car_image_3' => 'nullable|string',
            'car_image_4' => 'nullable|string',
            'driver_license_number' => 'nullable|string',
            'driver_license_front' => 'nullable|string',
            'driver_license_back' => 'nullable|string',
            'driver_license_date' => 'nullable|date',
            'ts_passport_front' => 'nullable|string',
            'ts_passport_back' => 'nullable|string',
        ]);

        $txDriverModeration = TxDriverModeration::updateOrCreate(
            ['user_id' => $validatedData['user_id']],
            array_merge($validatedData, ['status' => 'preparation'])
        );

        return response()->json($txDriverModeration, 200);
    }
}
