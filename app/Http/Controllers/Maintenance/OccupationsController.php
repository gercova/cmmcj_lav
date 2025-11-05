<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\OccupationValidate;
use App\Http\Resources\OccupationResource;
use App\Models\Occupation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OccupationsController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:ocupacion_acceder')->only('index');
		$this->middleware('permission:ocupacion_ver')->only('see', 'list', 'show');
        $this->middleware('permission:ocupacion_guardar')->only('store');
		$this->middleware('permission:ocupacion_borrar')->only('destroy');
    }

    public function index(): View {
        return view('maintenance.occupations.index');
    }

    public function list(): JsonResponse {
        $results 	= Occupation::all();
        $user       = auth()->user();
        $canEdit    = $user->can('ocupacion_editar');
        $canDelete  = $user->can('ocupacion_borrar');
        
        $data = $results->map(function ($item, $index) use ($canEdit, $canDelete) {
            return [
                $index + 1,
                $item->descripcion,
                $item->created_at->format('Y-m-d H:i:s'),
                $this->buildActionDropdown($item->id, $canEdit, $canDelete),
            ];
        });

        return response()->json([
            "sEcho"					=> 1,
            "iTotalRecords"			=> $data->count(),
            "iTotalDisplayRecords"	=> $data->count(),
            "aaData"				=> $data,
        ]);
    }

    protected function buildActionDropdown($id, $canEdit, $canDelete): string {
        if (!$canEdit && !$canDelete) return '<span class="text-muted">Sin acciones</span>';
        
        $buttons = [];
        if ($canEdit) {
            $buttons[] = '<li><a class="dropdown-item update-row" type="button" value="'.e($id).'"><i class="bi bi-pencil-square"></i> Editar</a></li>';
        }
        if ($canDelete) {
            $buttons[] = '<li><a class="dropdown-item delete-occupation" type="button" value="'.e($id).'"><i class="bi bi-trash"></i> Eliminar</a></li>';
        }
        
        return '<div class="btn-group">
            <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">Acciones</button>
            <ul class="dropdown-menu">'.implode('', $buttons).'</ul>
        </div>';
    }

    public function store(OccupationValidate $request): JsonResponse {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $result = Occupation::updateOrCreate(['id' => $request->input('id')], $validated);
            DB::commit();

            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Registro actualizado correctamente' : 'Registro creado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => 'Error al guardar el registro',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(Occupation $occupation): JsonResponse {
        return response()->json(OccupationResource::make($occupation));
    }

    public  function destroy (Occupation $occupation): JsonResponse {
        $occupation->delete();
        return response()->json([
            'status'    => (bool) $occupation,
            'type'      => $occupation ? 'success' : 'error',
            'message'   => $occupation ? 'Ocupación eliminada' : 'Error al eliminar ocupación',
        ], $occupation ? 200 : 400);
    }
}
