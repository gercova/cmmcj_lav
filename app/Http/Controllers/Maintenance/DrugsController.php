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
    }

    public function index(): View {
        $um = UnitofMeasure::all();
        return view('maintenance.drugs.index', compact('um'));
    }

    public function list(){
        $results 	= DB::table('view_active_drugs')->get();
        $data       = $results->map(function ($item, $index) {
            $buttons = '';
            //$user = auth()->user();
            //if ($user->can('ocupacion_actualizar')) {
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-sm btn-warning update-row btn-md" value="%s" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </button> ',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            //}
            //if ($user->can('ocupacion_borrar')) {
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-sm btn-danger delete-occupation btn-md" value="%s" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            //}
            
            return [
                $index + 1,
                $item->unidad,
                $item->farmaco,
                $item->created_at,
                $buttons ?: 'No hay acciones disponibles',
            ];
        });

        return response()->json([
            "sEcho"					=> 1,
            "iTotalRecords"			=> $data->count(),
            "iTotalDisplayRecords"	=> $data->count(),
            "aaData"				=> $data,
       ]);
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
        ]);
    }
}
