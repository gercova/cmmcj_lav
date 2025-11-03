<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalizationValidate;
use App\Http\Resources\HospitalizationResource;
use App\Models\History;
use App\Models\Hospitalization;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HospitalizationsController extends Controller {
    
    public function __construct(){
        $this->middleware(['auth', 'prevent.back']);
    }

    public function index(): View {
        return view('EMR.hospitalizations.index');
    }

    public function new(History $history): View {
        return view('EMR.hospitalizations.new', compact('history'));
    }

    public  function edit(Hospitalization $hospitalization): View {
        $history = History::find($hospitalization->history_id);
        return view('EMR.hospitalizations.edit', compact('hospitalization', 'history'));
    }

    public function see(History $history): View {
        return view('EMR.hospitalizations.see', compact('history'));
    }

    public function list(History $history): JsonResponse {
        $results 		= DB::select('CALL PA_getExamsbyMedicalHistory(?)', [$history->id]);
		$data 			= collect($results)->map(function ($item, $index) {
			$user   	= auth()->user();
			$buttons 	= '';
			//if($user->can('examen_ver')){
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-info view-exam btn-xs" value="%s"><i class="bi bi-eye"></i> Ver informe</button>&nbsp;',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            //}
			//if($user->can('examen_editar')){
                $buttons .= sprintf(
                    '<a type="button" class="btn btn-warning btn-xs" href="%s"><i class="bi bi-pencil-square"></i> Editar</a>&nbsp;',
                    htmlspecialchars(route('emr.exams.edit', ['exam' => $item->id]), ENT_QUOTES, 'UTF-8'),
                );
            //}
			//if($user->can('examen_borrar')){
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-danger delete-exam btn-xs" value="%s"><i class="bi bi-trash"></i> Eliminar</button>',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            //}

			return [
				$index + 1,
                $item->created_at,
                $item->dni,
                $item->descripcion,
                $buttons ?: '<span class="text-muted">No autorizado</span>'
			];
		});

		return response()->json([
 			"sEcho"					=> 1,
 			"iTotalRecords"			=> $data->count(),
 			"iTotalDisplayRecords"	=> $data->count(),
 			"aaData"				=> $data ?? [],
 		], 200);
    }

    public function store(HospitalizationValidate $request): JsonResponse {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $result = Hospitalization::updateOrCreate(['id' => $request->input('id')], $validated);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Hospitalización actualizada correctamente' : 'Hospitalización registrada correctamente',
                'route'     => route('emr.hospitalizations.see', $result->history_id)
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(Hospitalization $hospitalization): JsonResponse {
        $hospitalization->load('historias'); // Carga la relación
        return response()->json(HospitalizationResource::make($hospitalization), 200);
    }

    public function destroy(Hospitalization $hospitalization): JsonResponse {
        $hospitalization->delete();
        return response()->json([
            'status'    => (boolean) $hospitalization,
            'type'      => $hospitalization ? 'success' : 'error',
            'message'   => $hospitalization ? 'Registro eliminado correctament' : 'Error al eliminar el registro',
        ], $hospitalization ? 200 : 400);
    }

    public function printReportId(Hospitalization $hospitalization){


    }
}
