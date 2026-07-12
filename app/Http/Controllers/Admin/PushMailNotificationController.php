<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FirebaseNotificationService;
use App\Models\User;
use App\Models\Personal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPushNotification;

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
    protected function sendNotification($user, string $title, string $body, array $data = []): void
    {
        if (empty($user->device_token)) {
            return;
        }

        $sent = $this->firebase->sendNotification(
            $user->device_token,
            $title,
            $body,
            null,
            $data
        );

        if (!$sent) {
            Log::warning('Push notification failed', [
                'user_id' => $user->id,
                'title' => $title,
            ]);
        }
    
    }

    public function businessPushNotificationAllUsers(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:500',
            'channels' => 'required|array',
        ]);

        $delivery_channel = $validated["channels"];

        if (!is_array($delivery_channel) || empty($delivery_channel)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one delivery channel.',
            ]);
        }

        try {
            // All Business Users
            $businessUsers = User::select('id', 'business_name', 'email', 'device_token')->get();

            // Send In-App Notification
            if(in_array('inapp', $delivery_channel)){

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
            } 

            // Send Email
            if (in_array('email', $delivery_channel)) {
                $subjectTitle = $validated['subject'];
                $message = $validated['message'];

                foreach ($businessUsers as $user) {
                    Mail::to($user->email)->send(new AdminPushNotification($user->business_name, $subjectTitle, $message));
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Broadcast notification sent successfully',
            ]);

        } catch (\Throwable $th) {
            Log::error('Broadcast notification failed: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Broadcast notification failed',
            ]);
        }
    }


    //business single user
    public function businessPushNotificationSingleUser(Request $request, $id) {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:500',
            'channels' => 'required|array',
        ]);

        $delivery_channel = $validated["channels"];

        if (!is_array($delivery_channel) || empty($delivery_channel)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one delivery channel.',
            ]);
        }

        try {
            // All Business Users
            $businessUser = User::find($id);

            if (!$businessUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business user not found.',
                ], 404);
            }

            // Send In-App Notification
            if(in_array('inapp', $delivery_channel)){
                $this->sendNotification(
                    $businessUser,
                    $validated['subject'],
                    $validated['message'],
                    [
                        'type' => 'broadcast',
                        'user_id' => $businessUser->id,
                        'redirect_to' => '/',
                    ]
                );
            
            } 

            // Send Email
            if (in_array('email', $delivery_channel)) {
                $subjectTitle = $validated['subject'];
                $message = $validated['message'];
                Mail::to($businessUser->email)->send(new AdminPushNotification($businessUser->business_name, $subjectTitle, $message));
            }

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
            ]);

        } catch (\Throwable $th) {
            Log::error('Notification failed: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Notification failed',
            ]);
        }
    }


    //for personal section

    public function personalPushNotificationAllUsers(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:500',
            'channels' => 'required|array',
        ]);

        $delivery_channel = $validated["channels"];

        if (!is_array($delivery_channel) || empty($delivery_channel)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one delivery channel.',
            ]);
        }

        try {
            // All Personal Users
            $personalUsers = Personal::select('id', 'firstname', 'lastname', 'email', 'device_token')->get();

            // Send In-App Notification
            if(in_array('inapp', $delivery_channel)){

                foreach ($personalUsers as $p_user) {
                    $this->sendNotification(
                        $p_user,
                        $validated['subject'],
                        $validated['message'],
                        [
                            'type' => 'broadcast',
                            'user_id' => $p_user->id,
                            'redirect_to' => '/',
                        ]
                    );
                }
            } 

            // Send Email
            if (in_array('email', $delivery_channel)) {
                $subjectTitle = $validated['subject'];
                $message = $validated['message'];

                foreach ($personalUsers as $personaluser) {
                    $personal_name = $personaluser->firstname . ' ' . $personaluser->lastname;
                    Mail::to($personaluser->email)->send(new AdminPushNotification($personal_name, $subjectTitle, $message));
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Broadcast notification sent successfully',
            ]);

        } catch (\Throwable $th) {
            Log::error('Broadcast notification failed: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Broadcast notification failed',
            ]);
        }
    }


    //personal single user
    public function personalPushNotificationSingleUser(Request $request, $id) {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:500',
            'channels' => 'required|array',
        ]);

        $delivery_channel = $validated["channels"];

        if (!is_array($delivery_channel) || empty($delivery_channel)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one delivery channel.',
            ]);
        }

        try {
            // All Personal Users
            $personalUser = Personal::find($id);

            if (!$personalUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Personal user not found.',
                ], 404);
            }

            // Send In-App Notification
            if(in_array('inapp', $delivery_channel)){
                $this->sendNotification(
                    $personalUser,
                    $validated['subject'],
                    $validated['message'],
                    [
                        'type' => 'broadcast',
                        'user_id' => $personalUser->id,
                        'redirect_to' => '/',
                    ]
                );
            
            } 

            // Send Email
            if (in_array('email', $delivery_channel)) {
                $subjectTitle = $validated['subject'];
                $message = $validated['message'];
                $personal_name = $personalUser->firstname . ' ' . $personalUser->lastname;
                Mail::to($personalUser->email)->send(new AdminPushNotification($personal_name, $subjectTitle, $message));
            }

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
            ]);

        } catch (\Throwable $th) {
            Log::error('Notification failed: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Notification failed',
            ]);
        }
    }


}
