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
        $data       = $results->map(function ($item, $index) {
            $buttons = '';
            $user = auth()->user();
            if ($user->can('ocupacion_actualizar')) {
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-sm btn-warning update-row btn-md" value="%s" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </button> ',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }
            if ($user->can('ocupacion_borrar')) {
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-sm btn-danger delete-occupation btn-md" value="%s" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }
            
            return [
                $index + 1,
                $item->descripcion,
                $item->created_at->format('Y-m-d H:i:s'),
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
        ], 200);
    }
}
