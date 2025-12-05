<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuditController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Gate::allows('view-audit-logs')) {
                abort(403, 'No tienes permisos para ver la bitácora');
            }
            return $next($request);
        });
    }

    public function index(Request $request) {
        $query = AuditLog::with('user');

        // Filtros
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs   = $query->orderBy('created_at', 'desc')->paginate(50);
        $users  = User::whereHas('auditLogs')->get();
        $actions = AuditLog::distinct()->pluck('action');

        return view('audit.index', compact('logs', 'users', 'actions'));
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');

        return view('audit.show', compact('auditLog'));
    }

    public function export(Request $request) {
        $logs = AuditLog::with('user')
            ->whereBetween('created_at', [$request->date_from, $request->date_to])
            ->get();

        // Lógica para exportar a CSV o Excel
        // Implementar según tu necesidad
    }
}
