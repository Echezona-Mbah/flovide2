<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateBusinessSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $account = $request->user();

        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'code' => 'UNAUTHENTICATED',
                'data' => null,
            ], 401);
        }

        // Get the currently authenticated Sanctum token
        $token = $account->currentAccessToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication session could not be verified. Please log in again.',
                'code' => 'SESSION_INVALID',
                'data' => null,
            ], 401);
        }

        // Find the session ability attached to this token
        $sessionAbility = collect($token->abilities ?? [])
            ->first(function ($ability) {
                return str_starts_with($ability, 'session:');
            });

        if (!$sessionAbility) {
            return response()->json([
                'success' => false,
                'message' => 'Your session is no longer valid. Please log in again.',
                'code' => 'SESSION_EXPIRED',
                'data' => null,
            ], 401);
        }

        // Extract UUID from "session:UUID"
        $tokenSessionId = substr($sessionAbility, strlen('session:'));

        // Compare token session against the account's active session
        if (
            !$account->active_session_id ||
            !hash_equals(
                (string) $account->active_session_id,
                (string) $tokenSessionId
            )
        ) {
            // Delete this old token now that we know it is stale
            $token->delete();
            
            return response()->json([
                'success' => false,
                'message' => 'Your account was signed in on another device. Please log in again.',
                'code' => 'SESSION_EXPIRED',
                'data' => null,
            ], 401);
        }

        return $next($request);
    }
}