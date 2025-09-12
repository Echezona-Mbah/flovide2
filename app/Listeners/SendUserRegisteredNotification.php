<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Notifications\GeneralNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserRegisteredNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
     public function handle(UserRegistered $event)
    {
        $user = $event->user;

        // Example: send notification
        $user->notify(new GeneralNotification(
            "Welcome to Flovide 🎉",
            "Hello {$user->firstname}, your account has been created successfully!"
        ));
    }
}
