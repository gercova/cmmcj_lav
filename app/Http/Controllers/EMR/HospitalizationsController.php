<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalizationValidate;
use App\Http\Resources\HospitalizationResource;
use App\Models\Bed;
use App\Models\History;
use App\Models\Hospitalization;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HospitalizationsController extends Controller {
    
    public function __construct(){
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware([]);
        $this->middleware([]);
        $this->middleware([]);
        $this->middleware([]);
    }

    public function index(): View {
        return view('EMR.hospitalizations.index');
    }

    public function new(History $history): View {
        $beds = Bed::get();
        return view('EMR.hospitalizations.new', compact('history', 'beds'));
    }

    public  function edit(Hospitalization $hospitalization): View {
        $hc = History::find($hospitalization->historia_id);
        $beds = Bed::get();
        return view('EMR.hospitalizations.edit', compact('hospitalization', 'hc', 'beds'));
    }

    public function see(History $history): View {
        return view('EMR.hospitalizations.see', compact('history'));
    }

    public function listHospitalizations(History $history): JsonResponse {
        $results 		= DB::select('CALL PA_getHospitalizationsByMedicalHistory(?)', [$history->id]);
		$data 			= collect($results)->map(function ($item, $index) {
			$user   	= auth()->user();
			$buttons 	= '';
			if($user->can('hospitalizacion_ver')){
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-info view-hsp btn-xs" value="%s"><i class="bi bi-eye"></i> Ver informe</button>&nbsp;',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }
			if($user->can('hospitalizacion_editar')){
                $buttons .= sprintf(
                    '<a type="button" class="btn btn-warning btn-xs" href="%s"><i class="bi bi-pencil-square"></i> Editar</a>&nbsp;',
                    htmlspecialchars(route('emr.hospitalizations.edit', ['hospitalization' => $item->id]), ENT_QUOTES, 'UTF-8'),
                );
            }
			if($user->can('hospitalizacion_borrar')){
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-danger delete-hospitalization btn-xs" value="%s"><i class="bi bi-trash"></i> Eliminar</button>',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }

			return [
				$index + 1,
                $item->created_at,
                $item->dni,
                $item->bed,
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
                'route'     => route('emr.hospitalizations.see', $result->historia_id)
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
        $hospitalization->load('historias');
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
