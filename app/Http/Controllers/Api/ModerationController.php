<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TxDriverModeration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ModerationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $moderation = TxDriverModeration::where('user_id', $user->id)->first();

        if (!$moderation) {
            return response()->json(['message' => 'Moderation record not found'], 404);
        }

        return response()->json($moderation, 200);
    }
    public function store(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'nullable|string',
            'lastname' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'car_id' => 'nullable|exists:tx_catalog_cars,id',
            'car_model_id' => 'nullable|exists:tx_catalog_car_models,id',
            'car_vin' => 'nullable|string',
            'car_year' => 'nullable|integer',
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
            ['user_id' => $user->id],
            array_merge($validatedData, ['user_id' => $user->id, 'status' => 'preparation'])
        );

        return response()->json($txDriverModeration, 200);
    }

    public function uploadImage(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Проверка формата и размера файла
            'field_key' => 'nullable|string',  // Проверка наличия ключа поля
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Загрузка файла
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('moderation/user' . $user->id);

            if ($request->field_key) {
                $field_key =  $request->field_key;
                $moderation = TxDriverModeration::where('user_id', $user->id)->first();
                if (!$moderation) {
                    $moderation = TxDriverModeration::create(['user_id' => $user->id]);
                }
                // Удаление старой обложки
                if($moderation->{$field_key}) {
                    Storage::delete($moderation->{$field_key});
                }

                $moderation->update([
                    $field_key => $path,
                ]);

                return response()->json(['message' => 'Moderation file updated successfully', 'image_path' => $path], 200);
            }

            // Если team_id не указан, просто возвращаем путь до загруженной обложки
            return response()->json(['image_path' => $path], 200);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }

    public function deleteImage(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'field_key' => 'required|string',  // Проверка наличия ключа поля
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $field_key = $request->field_key;
        $moderation = TxDriverModeration::where('user_id', $user->id)->first();

        if (!$moderation || !$moderation->{$field_key}) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Удаление файла
        Storage::delete($moderation->{$field_key});

        // Обновление поля в базе данных
        $moderation->update([
            $field_key => null,
        ]);

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }

    public function setToModeration()
    {
        $user = Auth::user();

        $moderation = TxDriverModeration::where('user_id', $user->id)->first();

        if (!$moderation) {
            return response()->json(['message' => 'Moderation record not found'], 404);
        }

        $moderation->update(['status' => 'moderation']);

        return response()->json(['message' => 'Status updated to moderation'], 200);
    }
}
