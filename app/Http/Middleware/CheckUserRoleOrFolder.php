<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CheckUserRoleOrFolder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is authenticated via Sanctum
        if ($request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            if ($token) {
                $user = $token->tokenable;
            }
        }

        // Check if the user is authenticated and has the role 'ADM'
        if ($user && $user->role === 'ADM') {
            return $next($request);
        }

        // Check if the requested folder matches 'user<$user->id>'
        $userId = $user ? $user->id : null;
        $requestedFolder = $request->route('user_folder');

        if ($userId && $requestedFolder === 'user' . $userId) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
