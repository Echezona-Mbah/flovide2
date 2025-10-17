<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
     public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()->latest()->get();

        return response()->json([
          'data'=>[
              'status' => 'success',
            'data'   => $notifications
          ]
        ]);
    }

    public function unread()
    {
        $user = Auth::user();

        $notifications = $user->unreadNotifications()->latest()->get();

        return response()->json([
         'data'=>[
               'status' => 'success',
            'data'   => $notifications
         ]
        ]);
    }

    public function markAsRead($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'data'=>[
               'status' => 'success',
            'message' => 'Notification marked as read'
            ]
        ]);
    }
}
