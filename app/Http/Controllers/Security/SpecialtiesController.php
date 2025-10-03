<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialtyValidate;
use App\Http\Resources\SpecialtyResource;
use App\Models\Occupation;
use App\Models\Specialty;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecialtiesController extends Controller {

    public function __construct(){
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:permiso_acceder')->only('index');
		$this->middleware('permission:permiso_ver')->only('list', 'show');
		$this->middleware('permission:permiso_crear')->only('new');
		$this->middleware('permission:permiso_editar')->only('edit');
        $this->middleware('permission:permiso_guardar')->only('store');
		$this->middleware('permission:permiso_borrar')->only('destroy');
    }

    public function index(): View {
        $oc = Occupation::all();
        return view('security.specialty.index',compact('oc'));
    }

    public function list(): JsonResponse {
        $results    = Specialty::join('ocupaciones', 'especialidades.ocupacion_id', '=', 'ocupaciones.id')
            ->selectRaw('ocupaciones.descripcion ocupacion, especialidades.descripcion, especialidades.created_at, especialidades.id')
            ->where('especialidades.deleted_at', NULL)
            ->where('ocupaciones.deleted_at', NULL)
            ->get();
        $data       = $results->map(function ($item, $index) {
            $user   = auth()->user();
            $buttons = '';
            if($user->can('especialidad_editar')){
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-sm btn-warning update-row btn-md" value="%s">
                        <i class="bi bi-pencil-square"></i>
                    </button>&nbsp;',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }
            if($user->can('especialidad_borrar')){
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-sm btn-danger delete-specialty btn-md" value="%s">
                        <i class="bi bi-trash"></i>
                    </button>',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }

            return [
                $index + 1,
                $item->ocupacion,
                $item->descripcion,
                $item->created_at->format('Y-m-d H:i:s'),
                $buttons ?: 'No hay acciones disponibles'
            ];
        });

      	return response()->json([
 			"sEcho"					    => 1,
 			"iTotalRecords"			    => $data->count(),
 			"iTotalDisplayRecords"	    => $data->count(),
 			"aaData"				    => $data,
 		]);
    }

    public function store(SpecialtyValidate $request): JsonResponse {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $result = Specialty::updateOrCreate(['id' => $request->input('id')], $validated);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Especialidad actualizada' : 'Especialidad guardado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => 'Error al guardar los datos',
                'error'     => $th->getMessage(),
            ], 500);
        }
    }

    public function show(Specialty $specialty): JsonResponse {
        return response()->json(SpecialtyResource::make($specialty));
    }

    public function destroy(Specialty $specialty): JsonResponse {
        $specialty->delete();
        return response()->json([
            'status'    => (bool) $specialty,
            'type'      => $specialty ? 'success' : 'error',
            'message'   => $specialty ? 'Especialidad eliminada correctamente' : 'Hubo un problema al intentar eliminar el registro',
        ], $specialty ? 200 : 500);
    }
}
