<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\DrugValidate;
use App\Http\Resources\DrugResource;
use App\Models\Drug;
use App\Models\UnitofMeasure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrugsController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:farmaco_acceder')->only('index');
		$this->middleware('permission:farmaco_ver')->only('see', 'list', 'show', 'search');
        $this->middleware('permission:farmaco_guardar')->only('store');
		$this->middleware('permission:farmaco_borrar')->only('destroy');
    }

    public function index(): View {
        $um = UnitofMeasure::all();
        return view('maintenance.drugs.index', compact('um'));
    }

    /*public function list(){
        $results 	= DB::table('view_active_drugs')->get();
        $data       = $results->map(function ($item, $index) {
            $buttons = '';
            $user = auth()->user();
            if ($user->can('farmaco_editar')) {
                $buttons .= sprintf(
                    '<li><a class="dropdown-item update-row" type="button" value="%s"> <i class="bi bi-pencil-square"></i> Editar</a></li>',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }
            if ($user->can('farmaco_borrar')) {
                $buttons .= sprintf(
                    '<li><a class="dropdown-item delete-occupation" type="button" value="%s"> <i class="bi bi-trash"></i> Eliminar</a></li>',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }

            $button = sprintf(
                '<div class="btn-group">
                    <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones
                    </button>
                    <ul class="dropdown-menu">'.
                        $buttons ?: 'No hay acciones disponibles'
                    .'</ul>
                </div>',
            );
            
            return [
                $index + 1,
                $item->unidad,
                $item->farmaco,
                $item->created_at,
                $button,
            ];
        });

        return response()->json([
            "sEcho"					=> 1,
            "iTotalRecords"			=> $data->count(),
            "iTotalDisplayRecords"	=> $data->count(),
            "aaData"				=> $data,
        ]);
    }*/

    public function list() {
        $results    = DB::table('view_active_drugs')->get();
        $user       = auth()->user();
        $canEdit    = $user->can('farmaco_editar');
        $canDelete  = $user->can('farmaco_borrar');
        
        $data = $results->map(function ($item, $index) use ($canEdit, $canDelete) {
            return [
                $index + 1,
                $item->unidad,
                $item->farmaco,
                $item->created_at,
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
            //'<li><a class="dropdown-item delete-occupation" type="button" value="%s"> <i class="bi bi-trash"></i> Eliminar</a></li>',
        }
        
        return '<div class="btn-group">
            <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">Acciones</button>
            <ul class="dropdown-menu">'.implode('', $buttons).'</ul>
        </div>';
    }

    public function store(DrugValidate $request): JsonResponse {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $result = Drug::updateOrCreate(['id' => $request->input('id')], $validated);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Registro actualizado correctamente' : 'Registro creado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => 'Error al guardar el registro',
                'error'     => $th->getMessage(),
            ], 500);
        }
    }

    public function show(Drug $drug): JsonResponse {
        return response()->json($drug);
    }

    public function search(Request $request) {
        $drugs = Drug::where('farmacos.descripcion', 'like', '%'.$request->input('q').'%')
            ->join('unidad_medida', 'unidad_medida.id', '=', 'farmacos.unidad_medida_id')
            ->selectRaw('farmacos.id, UPPER(farmacos.descripcion) farmaco, UPPER(unidad_medida.descripcion) as unidad')
            ->limit(5)
            ->get();
        return DrugResource::collection($drugs);
    }

    public function destroy(Drug $drug): JsonResponse {
        $drug->delete();
        return response()->json([
            'status'    => (bool) $drug,
            'type'      => $drug ? 'success' : 'error',
            'message'   => $drug ? 'Registro eliminado correctamente' : 'Error al eliminar el registro',
        ], $drug ? 200 : 400);
    }
}
