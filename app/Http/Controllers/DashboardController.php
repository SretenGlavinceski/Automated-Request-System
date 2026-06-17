<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\NotificationLog;
use App\Models\Ticket;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $data = [
            'myTicketsCount' => Ticket::where('requester_id', $user->id)->count(),
            'myOpenTicketsCount' => Ticket::where('requester_id', $user->id)
                ->whereNotIn('status', ['approved', 'rejected', 'completed', 'closed'])
                ->count(),
            'unreadNotificationsCount' => $user->unreadNotifications()->count(),
        ];

        if ($user->canReviewTickets()) {
            $data['reviewTicketsCount'] = Ticket::where('reviewer_id', $user->id)
                ->count();

            $data['pendingReviewCount'] = Ticket::where('reviewer_id', $user->id)
                ->whereIn('status', [
                    'submitted',
                    'in_review',
                    'more_information_required',
                ])
                ->count();
        }

        if ($user->isAdmin()) {
            $data['allTicketsCount'] = Ticket::count();

            $data['failedNotificationsCount'] = NotificationLog::where(
                'status',
                'failed'
            )->count();

            $data['recentAuditLogs'] = AuditLog::with('user')
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('dashboard', $data);
    }
}
