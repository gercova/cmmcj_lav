<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalizationValidate;
use App\Http\Resources\HospitalizationResource;
use App\Models\AdmissionCondition;
use App\Models\AdmissionType;
use App\Models\Bed;
use App\Models\CarefulType;
use App\Models\DischargeCondition;
use App\Models\DischargeType;
use App\Models\Enterprise;
use App\Models\EntryCondition;
use App\Models\History;
use App\Models\Hospitalization;
use App\Models\HospitalizationStatus;
use App\Models\Insurance;
use App\Models\Service;
use App\Models\User;
use App\Models\ViaEntry;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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
        return view('emr.hospitalizations.index');
    }

    public function new(History $history): View {
        $beds = Bed::get();
        $seguro = Insurance::find($history->seguro_id);
        $at = AdmissionType::get();
        $ve = ViaEntry::get();
        $ec = EntryCondition::get();
        $se = Service::get();
        $ct = CarefulType::get();
        $dt = DischargeType::get();
        $ci = AdmissionCondition::get();
        $dc = DischargeCondition::get();
        $hs = HospitalizationStatus::get();
        return view('emr.hospitalizations.new', compact('history', 'beds', 'seguro', 'at', 've', 'ec', 'se', 'ct', 'dt', 'ci', 'dc', 'hs'));
    }

    public  function edit(Hospitalization $hospitalization): View {
        $hc = History::find($hospitalization->historia_id);
        $beds = Bed::get();
        $seguro = Insurance::find($hc->seguro_id);
        $at = AdmissionType::get();
        $ve = ViaEntry::get();
        $ec = EntryCondition::get();
        $se = Service::get();
        $ct = CarefulType::get();
        $dt = DischargeType::get();
        $ci = AdmissionCondition::get();
        $dc = DischargeCondition::get();
        $hs = HospitalizationStatus::get();
        return view('emr.hospitalizations.edit', compact('hospitalization', 'hc', 'beds', 'seguro', 'at', 've', 'ec', 'se', 'ct', 'dt', 'ci', 'dc', 'hs'));
    }

    public function see(History $history): View {
        return view('emr.hospitalizations.see', compact('history'));
    }

    public function listHospitalizations(History $history): JsonResponse {
        $results 		= DB::select('CALL PA_getHospitalizationsByMedicalHistory(?)', [$history->id]);
		$data 			= collect($results)->map(function ($item, $index) {
			$user   	= auth()->user();
			$buttons 	= '';
			if($user->can('hospitalizacion_ver')){
                $buttons .= sprintf(
                    '<a href="%s" class="btn btn-info view-hsp btn-xs" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Ver informe</a>&nbsp;',
                    htmlspecialchars(route('emr.hospitalizations.print', ['id' => $item->id]), ENT_QUOTES, 'UTF-8')
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
        $proccessFields = [
            't'     => isset($validated['t']) ? ($validated['t']) : '36.5° C',
            'so2'   => isset($validated['so2']) ? ($validated['so2']) : '98%',
            'fecha_admision' => isset($validated['fecha_admision']) ? ($validated['fecha_admision']) : now()->format('Y-m-d'),
            'user_id' => auth()->user()->id,
        ];

        $data = array_merge($validated, $proccessFields);
        DB::beginTransaction();
        try {
            $result = Hospitalization::updateOrCreate(['id' => $request->input('id')], $data);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Hospitalización actualizada correctamente' : 'Hospitalización registrada correctamente',
                'route'     => route('emr.hospitalizations.see', $result->historia_id),
                'route_print' => route('emr.hospitalizations.print', $result->id),
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
        $hospitalization->load('historia');
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

    public function printReportId(int $id, string $format = 'a5'){
        // Obtener datos de manera más eficiente
        $hsp        = Hospitalization::findOrFail($id);
        $hc         = History::findOrfail($hsp->historia_id);
        $user       = User::find($hsp->user_id);
        $en         = Enterprise::findOrFail(1);

		$pdf = PDF::loadView('emr.hospitalizations.pdf-a4', compact('hc', 'hsp', 'user', 'en'));
            $pdf->setPaper('a4', 'portrait')
                ->setOptions([
                    'margin_top'            => 10,
                    'margin_bottom'         => 10,
                    'margin_left'           => 15,
                    'margin_right'          => 15,
					'fontDefault'           => 'sans-serif',
                    'isHtml5ParserEnabled'  => true,
					'isRemoteEnabled'       => false,
					'isPhpEnabled'          => false,
					'chroot'                => realpath(base_path()),
                ]);

        $filename = "inform-hospitalizacion-{$id}-" . ".pdf";

        return $pdf->stream($filename);

    }
}
