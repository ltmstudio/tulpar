<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TxSms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function phoneToSms(Request $request)
    {
        try {
            $this->validate($request, [
                'phone' => 'required|string|min:10|max:10'
            ]);

            $user =  User::where(['phone' => $request->phone])->first();

            if (!$user) {
                $user = User::create(
                    [
                        'name' => 'User' . $request->phone,
                        'phone' => $request->phone . '',
                        'role' => 'CST',
                        'password' => bcrypt('password'),
                        'email' => 'user' . $request->phone . '@tulpar.system',
                        'ref' => 0
                    ]
                );
            }

            $sms = new TxSms;
            $sms->user_id = $user->id;
            $sms->sms = $sms->generateSms();
            $sms->salt = $sms->generateSalt();
            $expiredAt = Carbon::now()->addMinutes(3);
            $sms->expired_at = $expiredAt;
            $sms->active = 1;
            $sms->save();

            // TODO: Uncomment this line to send SMS
            // NodeServerService::sendSms($request->phone, 'APP: ' . $sms->sms);

            return response()->json([
                'success' => true,
                'message' => 'Смс отправлено',
                'data' => [
                    'salt' => $sms->salt,
                    // 'sms' => $sms->sms
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'phone' => 'required|string|min:10|max:10',
                'salt' => 'required|string|min:6|max:6',
                'sms' => 'required|string|min:6|max:6',
            ]);

            $user = User::where(['phone' => $request->phone])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Пользователь не найден'
                ], 404);
            }

            $sms = TxSms::where(['user_id' => $user->id, 'sms' => $request->sms, 'salt' => $request->salt, 'active' => 1])->first();

            if (!$sms) {
                return response()->json([
                    'success' => false,
                    'message' => 'Неверный смс код'
                ], 401);
            }

            if ($sms->isExpired()) {
                $sms->active = 0;
                $sms->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Код просрочен'
                ], 401);
            }

            TxSms::where('user_id', $user->id)->delete();

            $profile = $user;

            return response()->json([
                'success' => true,
                'message' => 'Регистрация прошла успешно',
                'data' => [
                    'token' => $user->createToken('authToken')->plainTextToken,
                    'profile' => $profile
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function user()
    {
        $user = Auth::user();

        if ($user == null) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Профиль пользователя получен',
            'data' => $user
        ]);
    }
}
