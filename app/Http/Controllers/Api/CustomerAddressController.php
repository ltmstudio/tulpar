<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TxCustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAddressController extends Controller
{
    public function getAddresses(Request $request)
    {
        $user = Auth::user();
        return response()->json($user->addresses);
    }

    public function addAddress(Request $request)
    {
        $user = Auth::user(); 

        $this->validate($request, [
            'address' => 'required|string|min:3|max:255',
            'geo' => 'sometimes|nullable|string|min:3|max:255'
        ]);

        TxCustomerAddress::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'geo' => $request->geo
        ]);

        return response()->json($user->addresses);
    }

    public function deleteAddress($id)
    {
        $user = Auth::user();
        $address = TxCustomerAddress::find($id);

        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        if ($address->user_id != $user->id) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        $address->delete();

        return response()->json($user->addresses);
    }
}
