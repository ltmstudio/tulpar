<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerGeoController extends Controller
{
    public function fetchLocationName(Request $request)
    {
        //validate request
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        $apiKey = 'b12bc3b5-9649-4306-8582-50b459eb4f9b';
        $response = Http::get('https://geocode-maps.yandex.ru/1.x/', [
            'apikey' => $apiKey,
            'geocode' => "{$request->longitude},{$request->latitude}",
            'format' => 'json'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $geoObjects = $data['response']['GeoObjectCollection']['featureMember'];

            if (!empty($geoObjects)) {
                // Берем первый элемент из списка
                $firstGeoObject = $geoObjects[0]['GeoObject'];
                $address = $firstGeoObject['metaDataProperty']['GeocoderMetaData']['text'];
                $address = str_replace(['Ашхабад, ', 'Казахстан, '], '', $address);
                return response()->json(['address' => $address]);
            }
            return response()->json(['error' => 'Адрес с точностью "exact" не найден'], 404);
        } else {
            return response()->json(['error' => 'Ошибка при обращении к API Яндекса'], 403);
        }
    }

    public function fetchLocationNameNominatim(Request $request)
    {
        //validate request
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'lang' => 'sometimes|nullable|string',
        ]);

        $urlString = 'https://nominatim.openstreetmap.org/reverse?lat=' . $request->latitude . '&lon=' . $request->longitude . '&format=json';

        $response = Http::withHeaders([
            'User-Agent' => 'TULPAR/1.0',
            // 'Accept-Language' => 'ru'
            'Accept-Language' => $request->lang ?? 'en'
        ])->get($urlString);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['display_name'])) {
                $address = $data['display_name'];
                $address = preg_replace('/, 050.*/', '', $address);
                $address = preg_replace('/, 500.*/', '', $address);
                return response()->json(['address' => $address]);
            }
            return response()->json(['error' => 'Адрес не найден', 'response' => $data], 404);
        } else {
            return response()->json([
                'error' => 'Ошибка при обращении к API Nominatim',
                'url' => $urlString,
                'response' => $response,
                'status' => $response->status() 
            ], 403);
        }
    }


    public function fetchRoute(Request $request)
    {
        //validate request
        $request->validate([
            'start_latitude' => 'required|numeric',
            'start_longitude' => 'required|numeric',
            'end_latitude' => 'required|numeric',
            'end_longitude' => 'required|numeric',
        ]);

        $start = [$request->start_longitude, $request->start_latitude];
        $end = [$request->end_longitude, $request->end_latitude];

        $urlString = 'https://router.project-osrm.org/route/v1/driving/' . implode(',', $start) . ';' . implode(',', $end) . '?overview=full&geometries=geojson';

        $response = Http::get($urlString);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['routes'][0])) {
                $route = $data['routes'][0];
                return response()->json(['route' => $route]);
            }
            return response()->json(['error' => 'Маршрут не найден', 'response' => $data], 404);
        } else {
            return response()->json([
                'error' => 'Ошибка при обращении к API OSRM',
                'url' => $urlString,
                'response' => $response,
                'status' => $response->status()
            ], 403);
        }
    }
}
