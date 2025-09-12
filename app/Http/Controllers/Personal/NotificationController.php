<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
 public function index()
    {
        $personal = auth('personal-api')->user();

        $notifications = $personal->notifications()->latest()->get();

        return response()->json([
          'data'=>[
              'status' => 'success',
            'data'   => $notifications
          ]
        ]);
    }

    public function unread()
    {
        $personal = auth('personal-api')->user();

        $notifications = $personal->unreadNotifications()->latest()->get();

        return response()->json([
         'data'=>[
               'status' => 'success',
            'data'   => $notifications
         ]
        ]);
    }

    public function markAsRead($id)
    {
        $personal = auth('personal-api')->user();

        $notification = $personal->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'data'=>[
               'status' => 'success',
            'message' => 'Notification marked as read'
            ]
        ]);
    }
}
