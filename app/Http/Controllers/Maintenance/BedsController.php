<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\BedValidate;
use App\Http\Resources\BedResource;
use App\Models\Bed;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BedsController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:cama_acceder')->only('index');
		$this->middleware('permission:cama_ver')->only('list', 'show');
        $this->middleware('permission:cama_guardar')->only('store');
		$this->middleware('permission:cama_borrar')->only('destroy');
    }

    public function index(): View {
        return view('maintenance.beds.index');
    }

    public function list(): JsonResponse {
        $results    = Bed::get();
        $user       = auth()->user();
        $canEdit    = $user->can('cama_editar');
        $canDelete  = $user->can('cama_borrar');
        
        $data = $results->map(function ($item, $index) use ($canEdit, $canDelete) {
            return [
                $index + 1,
                $item->description,
                $item->floor,
                $item->detail,
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
            $buttons[] = '<li><a class="dropdown-item delete-bed" type="button" value="'.e($id).'"><i class="bi bi-trash"></i> Eliminar</a></li>';
        }
        
        return '<div class="btn-group">
            <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">Acciones</button>
            <ul class="dropdown-menu">'.implode('', $buttons).'</ul>
        </div>';
    }

    public function store(BedValidate $request): JsonResponse {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $result = Bed::updateOrCreate(['id' => $request->input('id')], $validated);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Información actualizada correctamente' : 'Información guardada correctamente',
            ], 200);
        } catch (\Throwable $th){
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => $th->getMessage(),
            ], 400);
        }
    }

    public function show(Bed $bed): JsonResponse {
        return response()->json(BedResource::make($bed), 200);
    }

    public  function destroy(Bed $bed): JsonResponse {
        $bed->delete();
        return response()->json([
            'status'    => true,
            'type'      => 'success',
            'message'   => 'Registro eliminado correctamente',
        ], 200);
    }
}
