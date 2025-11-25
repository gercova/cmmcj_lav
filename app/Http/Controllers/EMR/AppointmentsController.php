<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentValidate;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\AppointmentsStatus;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function getCalendarData(Request $request): JsonResponse {
        try {
            // Log para debugging
            Log::info('Request calendario recibido', [
                'start' => $request->input('start'),
                'end' => $request->input('end'),
                'status' => $request->input('status')
            ]);

            $start = $request->input('start');
            $end = $request->input('end');
            $statusFilter = $request->input('status');

            // Verificar estructura de tu tabla primero
            $query = DB::table('citas')
                ->join('historias', 'citas.historia_id', '=', 'historias.id')
                ->join('estados_cita', 'citas.estado_cita_id', '=', 'estados_cita.id')
                ->select([
                    'citas.id',
                    'citas.created_at',
                    'citas.estado_cita_id as status_id',
                    'estados_cita.descripcion as status',
                    'historias.dni',
                    'historias.nombres as patient_name'
                ]);

            // Si tienes los campos fecha_cita y hora_cita, úsalos
            // Si NO, usa created_at como fecha de la cita
            if (DB::getSchemaBuilder()->hasColumn('citas', 'fecha_cita')) {
                $query->addSelect('citas.fecha_cita as appointment_date');
                
                if (DB::getSchemaBuilder()->hasColumn('citas', 'hora_cita')) {
                    $query->addSelect('citas.hora_cita as appointment_time');
                }
            }

            // Aplicar filtros de fecha
            if ($start && $end) {
                if (DB::getSchemaBuilder()->hasColumn('citas', 'fecha_cita')) {
                    $query->whereBetween('citas.fecha_cita', [
                        Carbon::parse($start)->startOfDay(),
                        Carbon::parse($end)->endOfDay()
                    ]);
                } else {
                    // Si no existe fecha_cita, usa created_at
                    $query->whereBetween('citas.created_at', [
                        Carbon::parse($start)->startOfDay(),
                        Carbon::parse($end)->endOfDay()
                    ]);
                }
            }

            // Aplicar filtro de estado si existe
            if ($statusFilter) {
                $query->where('citas.estado_cita_id', $statusFilter);
            }

            $appointments = $query->get();

            Log::info('Citas encontradas', [
                'count' => $appointments->count(),
                'first' => $appointments->first()
            ]);

            // Formatear datos para FullCalendar
            $events = $appointments->map(function ($appointment) {
                // Determinar la fecha y hora
                if (isset($appointment->appointment_date)) {
                    $date       = $appointment->appointment_date;
                    $time       = $appointment->appointment_time ?? '09:00:00';
                    $dateTime   = Carbon::parse($date . ' ' . $time);
                } else {
                    // Usar created_at si no existe fecha_cita
                    $dateTime = Carbon::parse($appointment->created_at);
                }
                
                return [
                    'id'        => $appointment->id,
                    'title'     => $appointment->patient_name . ' - ' . $appointment->status,
                    'start'     => $dateTime->toIso8601String(),
                    'end'       => $dateTime->copy()->addHour()->toIso8601String(),
                    'status_id' => $appointment->status_id,
                    'status'    => $appointment->status,
                    'patient'   => $appointment->patient_name,
                    'dni'       => $appointment->dni,
                ];
            });

            return response()->json($events);

        } catch (\Exception $e) {
            Log::error('Error en getCalendarData', [
                'message'   => $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine()
            ]);

            return response()->json([
                'error'     => 'Error al cargar las citas',
                'message'   => $e->getMessage(),
                'debug'     => config('app.debug') ? $e->getTrace() : null
            ], 500);
        }
    }

    /**
     * Obtener detalles de una cita específica
     */
    public function getAppointmentDetails(int $id): JsonResponse {
        try {
            $query = DB::table('citas')
                ->join('historias', 'citas.historia_id', '=', 'historias.id')
                ->join('estados_cita', 'citas.estado_cita_id', '=', 'estados_cita.id')
                ->select([
                    'citas.id',
                    'citas.created_at',
                    'citas.estado_cita_id as status_id',
                    'estados_cita.descripcion as status',
                    'historias.dni',
                    'historias.nombres as patient_name'
                ])
                ->where('citas.id', $id);

            // Agregar campos opcionales si existen
            if (DB::getSchemaBuilder()->hasColumn('citas', 'fecha_cita')) {
                $query->addSelect('citas.fecha_cita as appointment_date');
            }
            
            if (DB::getSchemaBuilder()->hasColumn('citas', 'hora_cita')) {
                $query->addSelect('citas.hora_cita as appointment_time');
            }
            
            if (DB::getSchemaBuilder()->hasColumn('citas', 'motivo')) {
                $query->addSelect('citas.motivo as reason');
            }

            if (DB::getSchemaBuilder()->hasColumn('historias', 'telefono')) {
                $query->addSelect('historias.telefono as phone');
            }

            if (DB::getSchemaBuilder()->hasColumn('historias', 'email')) {
                $query->addSelect('historias.email');
            }

            $appointment = $query->first();

            if (!$appointment) {
                return response()->json(['error' => 'Cita no encontrada'], 404);
            }

            // Formatear respuesta
            $response = [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient_name,
                'dni' => $appointment->dni,
                'phone' => $appointment->phone ?? 'No registrado',
                'email' => $appointment->email ?? 'No registrado',
                'status_id' => $appointment->status_id,
                'status' => $appointment->status,
                'doctor_name' => 'No asignado',
                'reason' => $appointment->reason ?? 'Sin especificar',
            ];

            // Formatear fecha y hora
            if (isset($appointment->appointment_date)) {
                $response['appointment_date'] = Carbon::parse($appointment->appointment_date)->format('d/m/Y');
            } else {
                $response['appointment_date'] = Carbon::parse($appointment->created_at)->format('d/m/Y');
            }

            if (isset($appointment->appointment_time)) {
                $response['appointment_time'] = Carbon::parse($appointment->appointment_time)->format('H:i');
            } else {
                $response['appointment_time'] = Carbon::parse($appointment->created_at)->format('H:i');
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error en getAppointmentDetails', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Error al cargar los detalles',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchPatients(Request $request): JsonResponse {
        try {
            $term = $request->input('term');

            if (!$term || strlen($term) < 3) {
                return response()->json([]);
            }

            Log::info('Búsqueda de pacientes', ['term' => $term]);

            // OPCIÓN 1: Si tienes el campo 'nombres' (nombre completo)
            $patients = History::query()
                ->select([
                    'id',
                    'dni',
                    'nombres' // Ajusta según tu campo
                ])
                ->where(function($query) use ($term) {
                    $query->where('dni', 'LIKE', "%{$term}%")
                        ->orWhere('nombres', 'LIKE', "%{$term}%");
                })
                ->limit(10)
                ->get();

            /* 
            // OPCIÓN 2: Si tienes 'nombre' y 'apellido' separados
            $patients = DB::table('historias')
                ->select([
                    'id',
                    'dni',
                    DB::raw("CONCAT(nombre, ' ', apellido) as nombres")
                ])
                ->where(function($query) use ($term) {
                    $query->where('dni', 'LIKE', "%{$term}%")
                        ->orWhere('nombre', 'LIKE', "%{$term}%")
                        ->orWhere('apellido', 'LIKE', "%{$term}%");
                })
                ->limit(10)
                ->get();
            */

            /* 
            // OPCIÓN 3: Si usas 'documento' en lugar de 'dni'
            $patients = DB::table('historias')
                ->select([
                    'id',
                    'documento as dni', // Renombrar para mantener consistencia
                    'nombres'
                ])
                ->where(function($query) use ($term) {
                    $query->where('documento', 'LIKE', "%{$term}%")
                        ->orWhere('nombres', 'LIKE', "%{$term}%");
                })
                ->limit(10)
                ->get();
            */

            Log::info('Pacientes encontrados', ['count' => $patients->count()]);

            return response()->json($patients);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de pacientes', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'error' => 'Error al buscar pacientes',
                'message' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Método de prueba para verificar datos
     */
    public function testData(): JsonResponse {
        try {
            // Verificar estructura de la tabla
            $columns = DB::getSchemaBuilder()->getColumnListing('citas');
            
            // Obtener algunas citas de ejemplo
            $sampleAppointments = DB::table('citas')
                ->join('historias', 'citas.historia_id', '=', 'historias.id')
                ->join('estados_cita', 'citas.estado_cita_id', '=', 'estados_cita.id')
                ->select('citas.*', 'historias.nombres', 'estados_cita.descripcion')
                ->limit(5)
                ->get();

            return response()->json([
                'total_citas' => DB::table('citas')->count(),
                'columnas_tabla' => $columns,
                'citas_ejemplo' => $sampleAppointments,
                'fecha_hoy' => Carbon::now()->toDateString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
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
