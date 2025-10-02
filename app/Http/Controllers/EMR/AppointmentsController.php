<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentsController extends Controller {
    
    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        
    }

    public function index() {
        return view('emr.appointments.index');
    }

    public function store(): JsonResponse {

    }

    public function show(Appointment $appointment): JsonResponse {
        return response()->json(AppointmentResource::make($appointment));
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
