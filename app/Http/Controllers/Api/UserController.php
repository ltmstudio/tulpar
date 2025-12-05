<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TxSms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Str;

class UserController extends Controller
{
    public function phoneToSms(Request $request)
    {
        try {

            $test_users_phones = [
                '1234567890',
                '1234567891',
                '1234567892',
                '1234567893',
                '1234567894',
                '1234567899',
                '7073213436',
            ];

            $test_users_sms_code = 123456;

            $this->validate($request, [
                'phone' => 'required|string|min:10|max:10'
            ]);

            $user =  User::where(['phone' => $request->phone])->first();

            if (!$user) {
                // Проверяем уникальность сгенерированного email
                $generatedEmail = 'user' . $request->phone . '@tulpar.system';
                if (User::where('email', $generatedEmail)->exists()) {
                    $generatedEmail = NULL;
                }

                $user = User::create(
                    [
                        'name' => 'User' . $request->phone,
                        'phone' => $request->phone . '',
                        'role' => 'CST',
                        'password' => bcrypt('password'),
                        'email' => $generatedEmail, // Можно заменить на null, если email необязателен
                        'ref' => 0,
                        'auth_type' => 'phone', // Указываем тип авторизации
                    ]
                );
            }

            $sms = new TxSms;
            $sms->user_id = $user->id;
            if (in_array($request->phone, $test_users_phones)) {
                $sms->sms = $test_users_sms_code;
            } else {
                $sms->sms = $sms->generateSms();
                $sms_url = 'https://smsc.kz/sys/send.php';
                $sms_params = [
                    'login' => 'mukagali.orazbak',
                    'psw' => 'jxma83q2GzZ9HJK',
                    'phones' => '8' . $request->phone,
                    'mes' => 'TULPAR taxi: ' . $sms->sms,
                    'sender' => 'TULPAR'
                ];
                $query = http_build_query($sms_params);
                $response = file_get_contents($sms_url . '?' . $query);

                if (strpos($response, 'OK') === false) {
                    throw new \Exception('Ошибка отправки смс: ' . $response);
                }
            }
            $sms->salt = $sms->generateSalt();

            $expiredAt = Carbon::now()->addMinutes(3);
            $sms->expired_at = $expiredAt;
            $sms->active = 1;
            $sms->save();


            return response()->json([
                'success' => true,
                'message' => 'Смс отправлено',
                'data' => [
                    'salt' => $sms->salt,
                    'sms' => $sms->sms
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

    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при перенаправлении на Google',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function googleMobileAuth(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'access_token' => 'nullable|string',
                'id_token' => 'nullable|string',
                'email' => 'required|email',
                'name' => 'required|string',
                'phone' => 'nullable|string|min:4|max:20',
                'google_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Неверные данные',
                    'errors' => $validator->errors()
                ], 422);
            }

            $email = $request->email;
            $googleId = $request->google_id;
            $name = $request->name;
            $phone = $request->phone;
            // Находим или создаем пользователя
            $user = User::where('email', $email)->first();

            if ($user) {
                // Обновляем google_id если его нет
                if (!$user->google_id) {
                    $user->google_id = $googleId;
                    $user->auth_type = 'google';
                    $user->save();
                }
                if ($phone) {
                    $user->phone = $phone;
                    $user->save();
                }
            } else {
                // Создаем нового пользователя
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'auth_type' => 'google',
                    'role' => 'CST',
                    'ref' => 0,
                    'password' => bcrypt(Str::random(16)),
                ]);
                if ($phone) {
                    $user->phone = $phone;
                    $user->save();
                }
            }

            // Создаем токен
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Авторизация через Google прошла успешно',
                'data' => [
                    'token' => $token,
                    'profile' => $user
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Google Mobile Auth Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка авторизации через Google',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Новый метод для регистрации через Apple (улучшенная версия)
    public function appleMobileAuth(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['nullable', 'string', 'email', 'max:255'],
                'apple_id' => ['required', 'string'],
                'phone' => 'nullable|string|min:4|max:20',
                // 'auth_type' => ['required', 'in:apple'],
            ]);

            // Проверяем, существует ли пользователь с таким apple_id
            $user = User::where('apple_id', $request->apple_id)->first();

            if (!$user) {
                // Если пользователь не найден по apple_id, проверяем по email
                if ($request->email) {
                    $existingUser = User::where('email', $request->email)->first();

                    if ($existingUser) {
                        // Если пользователь существует с этим email, но без apple_id
                        $existingUser->apple_id = $request->apple_id;
                        $existingUser->auth_type = 'apple';
                        $existingUser->save();
                        $user = $existingUser;
                    } else {
                        // Создаем нового пользователя
                        $user = User::create([
                            'name' => $request->name,
                            'email' => $request->email ?? null,
                            'apple_id' => $request->apple_id,
                            'auth_type' => 'apple',
                            'role' => 'CST',
                            'ref' => 0,
                            'password' => bcrypt('password'), // Генерируем случайный пароль
                        ]);
                        if ($request->phone) {
                            $user->phone = $request->phone;
                            $user->save();
                        }
                    }
                } else {
                    // Создаем нового пользователя без email (Apple может не предоставить email)
                    $user = User::create([
                        'name' => $request->name,
                        'email' => null,
                        'apple_id' => $request->apple_id,
                        'auth_type' => 'apple',
                        'role' => 'CST',
                        'ref' => 0,
                        'password' => bcrypt('password'),
                    ]);
                    if ($request->phone) {
                        $user->phone = $request->phone;
                        $user->save();
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Регистрация через Apple прошла успешно',
                'data' => [
                    'token' => $user->createToken('authToken')->plainTextToken,
                    'profile' => $user
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

    public function googleCallback(Request $request)
        {
            try {
                $googleUser = Socialite::driver('google')->user();

                if (!$googleUser->email) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email не предоставлен Google аккаунтом',
                    ], 400);
                }

                // Находим или создаем пользователя
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Если пользователь существует с этим email
                    $existingUser->google_id = $googleUser->id;
                    $existingUser->auth_type = 'google';
                    $existingUser->save();
                    $user = $existingUser;
                } else {
                    // Создаем нового пользователя
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email ?? null,
                        'google_id' => $googleUser->id,
                        'auth_type' => 'google',
                        'role' => 'CST',
                        'ref' => 0,
                        'password' => bcrypt(Str::random(16)), // Более безопасный пароль
                    ]);
                }

                // Создаем токен
                $token = $user->createToken('authToken')->plainTextToken;

                // Возвращаем JSON ответ
                return response()->json([
                    'success' => true,
                    'message' => 'Авторизация через Google прошла успешно',
                    'data' => [
                        'token' => $token,
                        'profile' => $user
                    ]
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка авторизации через Google',
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
