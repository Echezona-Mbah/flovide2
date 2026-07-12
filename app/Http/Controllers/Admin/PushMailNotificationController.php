<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FirebaseNotificationService;
use App\Models\User;
use App\Models\Personal;
use Illuminate\Support\Facades\Log;

class PushMailNotificationController extends Controller
{
    protected $firebase;

    public function __construct(
        FirebaseNotificationService $firebase
    )
    {
        $this->firebase = $firebase;
    }
    
    
    //Shared notification helper
    protected function sendNotification(User $user, string $title, string $body, array $data = []): void
    {
        if ($user->device_tokens->isEmpty()) {
            return;
        }

        foreach ($user->device_tokens as $deviceToken) {

            $sent = $this->firebase->sendNotification(
                $deviceToken->token,
                $title,
                $body,
                null,
                $data
            );

            if (!$sent) {
                Log::warning('Push notification failed', [
                    'user_id' => $user->id,
                    'device_token_id' => $deviceToken->id,
                    'title' => $title,
                ]);
            }
        }
    }

    public function sendBroadcastNotification(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'delivery_channel' => 'required|array',
            'message' => 'required|string|max:500',
        ]);

        $delivery_channel = $request->delivery_channel;

        $businessUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Business');
        })->with('device_tokens')->get();

        foreach ($businessUsers as $user) {

            $this->sendNotification(
                $user,
                $validated['subject'],
                $validated['message'],
                [
                    'type' => 'broadcast',
                    'user_id' => $user->id,
                    'redirect_to' => '/',
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Broadcast notification sent successfully',
        ]);
    }
}
