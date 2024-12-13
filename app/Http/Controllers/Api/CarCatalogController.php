<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TxCatalogCar;
use Illuminate\Http\Request;

class CarCatalogController extends Controller
{
    public function index()
    {
        return response()->json(
            TxCatalogCar::where('popular', true)->get(),
        );
    }

    public function all()
    {
        return response()->json(
            TxCatalogCar::all()
        );
    }

    public function show($id)
    {
        return response()->json(
            TxCatalogCar::with('models')->find($id),
        );
    }

    public function search(Request $request)
    {
        $query = TxCatalogCar::query();

        if ($request->has('search')) {
            $search = $request->input('search', '');
            $searchTerms = explode(' ', $search);
            foreach ($searchTerms as $term) {
                $query->where(function ($query) use ($term) {
                    $query->orWhere('name', 'like', '%' . $term . '%')
                        ->orWhere('cyrillic-name', 'like', '%' . $term . '%')
                        ->orWhereHas('models', function ($query) use ($term) {
                            $query->where('name', 'like', '%' . $term . '%')
                                ->orWhere('cyrillic-name', 'like', '%' . $term . '%');
                        });
                });
            }
            $results = $query->with(['models' => function ($query) use ($searchTerms) {
                $query->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->orWhere('name', 'like', '%' . $term . '%')
                            ->orWhere('cyrillic-name', 'like', '%' . $term . '%');
                    }
                });
            }])->get();
            return response()->json($results);
        }
        return response()->json(
            ['message' => 'No search parameter provided'],
            400
        );
    }
}
