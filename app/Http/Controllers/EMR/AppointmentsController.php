<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentValidate;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\AppointmentsStatus;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentsController extends Controller {
    
    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:hospitalizacion_acceder')->only('index');
		$this->middleware('permission:hospitalizacion_ver')->only('show');
		//$this->middleware('permission:hospitalizacion_crear')->only('new');
		//$this->middleware('permission:hospitalizacion_editar')->only('edit');
        $this->middleware('permission:hospitalizacion_guardar')->only('store');
		$this->middleware('permission:hospitalizacion_borrar')->only('destroy');
    }

    public function index(): View {
        return view('emr.appointments.index');
    }

    public function list(): JsonResponse {
        return response()->json([], 200);
    }

    public function listStatus(): JsonResponse {
        $results = AppointmentsStatus::get();
        return response()->json($results, 200);
    }

    public function store(AppointmentValidate $request): JsonResponse {
        $validated = $request->validated();
        DB::beginTransaction();
		// Validar si ya existe cita para una determinada hora
		$validate = Appointment::where('historia_id', $validated['historia_id'])
            ->where('estado_cita_id', 1)
            ->where('fecha', $validated['fecha'])
            ->where('fecha', $validated['hora'])
            ->count();
		
		if ($validate > 0) {
			return response()->json([
				'status' 	=> false,
				'type' 		=> 'error',
				'messages' 	=> 'El paciente ya se encuentra agendado para esa fecha y hora.',
			], 422);
		}

        try {
            $result = Appointment::updateOrCreate(['id' => $request->input('cita_id')], $validated);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'La cita fue actualizada correctamente' : 'La cita fue agendada correctamente',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => $th->getMessage(),
            ], 400);
        }
    }

	public function checkAppointmentsStatus(Request $request, int $id): JsonResponse {
		$result = Appointment::where('id', $id)->update(['estado_cita_id' => '2']);
		return response()->json([
			'status' 	=> (bool) $result,
			'type'		=> $result ? 'success' : 'error',
			'messages' 	=> $result ? 'Actualizado exitosamente' : 'No se encontró el registro o no hubo cambios',
		]);
	}

    public function show(Appointment $appointment): JsonResponse {
        return response()->json(AppointmentResource::make($appointment));
    }

    public function getQuotes(): JsonResponse  {
        $results = Appointment::query()
            ->join('historias', 'citas.historia_id', '=', 'historias.id')
            ->join('estados_cita', 'citas.estado_cita_id', '=', 'estados_cita.id')
            ->select([
                'historias.dni',
                'historias.nombres',
                'citas.created_at',
                'citas.estado_cita_id',
                'estados_cita.descripcion as btn_status',
                'citas.id',
                'historias.id as hid'
            ])
            ->whereDate('citas.created_at', Carbon::today())
            ->whereIn('citas.estado_cita_id', [1, 2, 3])
            ->orderByDesc('citas.created_at')
            ->get();

        $data = $results->map(fn($item, $index) => [
            $index + 1,
            $item->dni,
            $item->nombres,
            $item->created_at->format('Y-m-d H:i:s'),
            $this->getStatusButton($item->estado_cita_id, $item->btn_status),
            $this->getActionButtons($item->id, $item->hid, $item->estado_cita_id)
        ]);

        return response()->json([
            'sEcho'                 => 1,
            'iTotalRecords'         => $data->count(),
            'iTotalDisplayRecords'  => $data->count(),
            'aaData'                => $data,
        ]);
    }

    private function getStatusButton(int $statusId, string $statusText): string {
        $statusClasses = [
            1 => 'btn-warning',
            2 => 'btn-success',
            3 => 'btn-info',
        ];

        $class = $statusClasses[$statusId] ?? 'btn-secondary';
        return sprintf(
            '<button class="btn btn-xs %s"><b>%s</b></button>',
            $class,
            e($statusText)
        );
    }

    private function getActionButtons(int $appointmentId, int $historyId, int $statusId): string {
        $examUrl = e(route('emr.exams.new', ['history' => $historyId]));
        $hospitalizationUrl = e(route('emr.hospitalizations.new', ['history' => $historyId]));
        $editUrl = e(route('emr.histories.edit', ['history' => $historyId]));

        // Botón de cambiar estado (solo si no está en estado 2)
        $changeStatusButton = $statusId !== 2 
        ? '<button type="button" class="btn btn-sm btn-warning changeStatus btn-md" value="' . $appointmentId . '">
                <i class="bi bi-check-square"></i> Cambiar estado de cita
           </button>&nbsp;'
        : '';

        return <<<HTML
            <div class="btn-group">
                {$changeStatusButton}
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        Acciones&nbsp;
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{$examUrl}">
                            <i class="bi bi-plus-square"></i> Nuevo examen
                        </a>
                        <a class="dropdown-item" href="{$hospitalizationUrl}">
                            <i class="bi bi-plus-square"></i> Nueva hospitalización
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{$editUrl}">
                            <i class="bi bi-pencil-square"></i> Editar Historia
                        </a>
                    </div>
                </div>
            </div>
        HTML;
    }

    public function destroy(Appointment $appointment): JsonResponse {
        $appointment->delete();
        return response()->json([
            'status'    => (bool) $appointment,
            'type'      => $appointment ? 'success' : 'error',
            'message'   => $appointment ? 'Cita eliminada correctamente' : 'Error al eliminar la cita',
        ], $appointment ? 200 : 400);
    }
}
