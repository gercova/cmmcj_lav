<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DiagnosticExam;
use App\Models\Exam;
use App\Models\History;
use App\Models\MaritalStatus;
use App\Models\MedicationExam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ReportsController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:examen_acceder')->only('index');
		$this->middleware('permission:examen_ver')->only('see', 'view', 'listExams', 'listDiagnostics', 'listMedications');
		$this->middleware('permission:examen_crear')->only('new');
		$this->middleware('permission:examen_editar')->only('edit');
        $this->middleware('permission:examen_guardar')->only('store');
		$this->middleware('permission:examen_borrar')->only('destroy', 'destroyDiagnostics', 'destroyMedications', 'destroyDocument');
    }

    public function index() {
        $yr = $this->years();
        return view('emr.reports.index', compact('yr'));
    }

    public function years(){
        return History::selectRaw('YEAR(created_at) as year')->groupBy('year')->orderBy('year', 'desc')->get();
    }

    public function getCountRows(): JsonResponse {
        $fechaActual    = now()->toDateString();
        $hc             = History::whereNull('deleted_at')->count();
        $ex             = Exam::whereNull('deleted_at')->count();
        $ap             = Appointment::whereNull('deleted_at')->count();
        $cd             = History::whereNull('deleted_at')->whereDate('created_at', $fechaActual)->count();
        $dx             = DiagnosticExam::whereNull('deleted_at')->count();
        $mx             = MedicationExam::whereNull('deleted_at')->count();
        return response()->json(compact('hc', 'ex', 'ap', 'cd', 'dx', 'mx'), 200);    
    }

    public function getMonthlyCountsByYear($year, $model, $name) {
        // Validar que el modelo sea válido (opcional, pero recomendado)
        if (!class_exists($model)) {
            throw new InvalidArgumentException("El modelo '{$model}' no existe.");
        }
        // Consulta genérica: cuenta por mes
        $records = $model::query()
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        // Inicializamos array de 12 meses con ceros
        $data = array_fill(0, 12, 0);
        // Asignamos los conteos reales a los meses correspondientes
        foreach ($records as $record) {
            $data[$record->month - 1] = (int) $record->count;
        }
        // Retornamos en el formato solicitado: array de objetos con name y data
        return [
            [
                'name' => $name,
                'data' => $data
            ]
        ];
    }

    public function getAnnualData($year) {
        $histories      = $this->getMonthlyCountsByYear($year, History::class, 'Historias');
        $exams          = $this->getMonthlyCountsByYear($year, Exam::class, 'Exámenes');
        $appointments   = $this->getMonthlyCountsByYear($year, Appointment::class, 'Citas');
        return array_merge($histories, $exams, $appointments);
    }

    public function getDiagnosticsByExam(){
        $diagnostics = DiagnosticExam::selectRaw('d.descripcion as name, COUNT(examen_diagnostico.diagnostico_id) as y')
            ->join('diagnosticos as d', 'd.id', '=', 'examen_diagnostico.diagnostico_id')
            ->whereNull('examen_diagnostico.deleted_at')
            ->groupBy('d.descripcion')
            ->having('y', '>', 0)
            ->orderBy('y', 'desc')
            ->limit(10)
            ->get();

        // Transforma los resultados al formato que Highcharts espera
        $data = $diagnostics->map(function ($item) {
            return [
                'name' => $item->name,
                'y' => (int) $item->y, // Asegura que sea número entero
            ];
        })->toArray();

        return response()->json([
            'series' => [
                [
                    'name' => 'Diagnósticos',
                    'data' => $data
                ]
            ]
        ]);
    }

    public function getDrugsByExam(){
        $medications = MedicationExam::selectRaw('f.descripcion as name, COUNT(*) as y')
            ->join('farmacos as f', 'f.id', '=', 'examen_medicacion.farmaco_id')
            ->whereNull('examen_medicacion.deleted_at')
            ->groupBy('name')
            ->having('y', '>', '0')
            ->orderBy('y', 'desc')
            ->limit(10)
            ->get();
        // Transforma los resultados al formato que Highcharts espera
        $data = $medications->map(function ($item) {
            return [
                'name' => $item->name,
                'y' => (int) $item->y, // Asegura que sea número entero
            ];
        })->toArray();

        return response()->json([
            'series' => [
                [
                    'name' => 'Farmacos',
                    'data' => $data
                ]
            ]
        ]);
    }

    /*public function getHistoriesBySex(){
        return History::selectRaw('s.descripcion as sexo, COUNT(historias.id_sexo) as cantidad')
            ->join('sexo as s', 's.id', '=', 'historias.id_sexo')
            ->groupBy('sexo')
            ->get();
    }*/

    public function getHistoriesByMaritalStatus(){
        $maritalStatus = History::selectRaw('e.descripcion as name, COUNT(historias.estado_civil_id) as y')
            ->join('estado_civil as e', 'e.id', '=', 'historias.estado_civil_id')
            ->groupBy('name')
            ->having('y', '>', '0')
            ->orderBy('y', 'desc')
            ->get();
        // Transforma los resultados al formato que Highcharts espera
        $data = $maritalStatus->map(function ($item) {
            return [
                'name' => $item->name,
                'y' => (int) $item->y, // Asegura que sea número entero
            ];
        })->toArray();

        return response()->json([
            'series' => [
                [
                    'name' => 'Estado',
                    'data' => $data
                ]
            ]
        ]);
    }

    public function getHistoriesByBloodingGroup(){
        $bloodingGroups = History::selectRaw('gs.descripcion as name, COUNT(historias.grupo_sanguineo_id) as y')
            ->join('grupos_sanguineos as gs', 'gs.id', '=', 'historias.grupo_sanguineo_id')
            ->groupBy('name')
            ->having('y', '>', '0')
            ->orderBy('y', 'desc')
            ->get();
        // Transforma los resultados al formato que Highcharts espera
        $data = $bloodingGroups->map(function ($item) {
            return [
                'name'  => $item->name,
                'y'     => (int) $item->y, // Asegura que sea número entero
            ];
        })->toArray();

        return response()->json([
            'series' => [
                [
                    'name' => 'Grupo Sanguíneo',
                    'data' => $data
                ]
            ]
        ]);
    }

    public function getHistoriesByDegreeIntruction(){
        $degreesInstruccion = History::selectRaw('di.descripcion as name, COUNT(historias.grado_instruccion_id) as y')
            ->join('grados_instruccion as di', 'di.id', '=', 'historias.grado_instruccion_id')
            ->groupBy('name')
            ->having('y', '>', 0)
            ->orderBy('y', 'desc')
            ->get();
        // Transforma los resultados al formato que Highcharts espera
        $data = $degreesInstruccion->map(function ($item) {
            return [
                'name'  => $item->name,
                'y'     => (int) $item->y, // Asegura que sea número entero
            ];
        })->toArray();

        return response()->json([
            'series' => [
                [
                    'name' => 'Grado',
                    'data' => $data
                ]
            ]
        ]);
    }

    public function getHistoriesByMAC(){
        $mac = Exam::selectRaw('m.descripcion as name, COUNT(examenes.mac_id) as y')
            ->join('mac as m', 'm.id', '=', 'examenes.mac_id')
            ->groupBy('name')
            ->having('y', '>', 0)
            ->orderBy('y', 'desc')
            ->get();
        // Transforma los resultados al formato que Highcharts espera
        $data = $mac->map(function ($item) {
            return [
                'name'  => $item->name,
                'y'     => (int) $item->y, // Asegura que sea número entero
            ];
        })->toArray();

        return response()->json([
            'series' => [
                [
                    'name' => 'Método',
                    'data' => $data
                ]
            ]
        ]);
    }
}
