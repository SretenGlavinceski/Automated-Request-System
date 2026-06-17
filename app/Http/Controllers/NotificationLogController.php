<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use Illuminate\View\View;

class NotificationLogController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $notificationLogs = NotificationLog::with([
            'ticket',
            'recipient',
        ])
            ->latest()
            ->get();

        return view(
            'notification-logs.index',
            compact('notificationLogs')
        );
    }
}
