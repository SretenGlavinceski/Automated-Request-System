<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function markAsRead(string $notificationId): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($notificationId);

        $notification->markAsRead();

        return redirect($notification->data['url']);
    }
}
