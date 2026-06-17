<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $auditLogs = AuditLog::with('user')
            ->latest()
            ->get();

        return view('audit-logs.index', compact('auditLogs'));
    }
}
