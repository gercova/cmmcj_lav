<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
    }

    public function todayActivities(): View {
        $activities = ActivityLog::with('user')
            ->today()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('H:i') . '|' . $item->formatted_action;
            });

        return view('admin.activity-logs.today', compact('activities'));
    }

    public function list(Request $request, User $user): JsonResponse {

        $results 		= '';
		$data 			= collect($results)->map(function ($item, $index) {
            $user 		= auth()->user();
			$buttons 	= '';
            $file       = '<a href="'. asset('storage/'.$item->documento) .'" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Ver archivo</a>';

				$buttons .= sprintf(
					'<button type="button" class="btn btn-danger delete-doc btn-xs" value="%s"><i class="bi bi-trash"></i> Eliminar</button>',
					$item->id
				);


			return [
                $item->nombre_examen,
                $file,
				$item->fecha_examen,
				$item->created_at,
                $buttons ?: '<span class="text-muted">No autorizado</span>',
			];
		});

        return response()->json([
            "sEcho"                 => 1,
            "iTotalRecords"         => $data->count(),
            "iTotalDisplayRecords"  => $data->count(),
            "aaData"                => $data->values() ?? [], // reindexar
        ], 200);
    }

    public function userActivities(Request $request, $userId): View {
        $user = User::findOrFail($userId);
        // Construir query con filtros
        $query = ActivityLog::with('user')->where('user_id', $userId);
        // Filtro por acción
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }
        // Filtro por módulo
        if ($request->has('module') && $request->module != '') {
            $query->where('module', 'like', '%' . $request->module . '%');
        }
        // Filtro por fecha
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.activity-logs.user', compact('activities', 'user'));
    }

    private function getColorCode($action) {
        $colors = [
            'access' => '#17a2b8',
            'create' => '#28a745',
            'update' => '#ffc107',
            'delete' => '#dc3545'
        ];

        return $colors[$action] ?? '#6c757d';
    }

    public function detail($id): View {
        $activity = ActivityLog::with('user')->findOrFail($id);
        return view('admin.activity-logs.detail', compact('activity'));
    }
}
