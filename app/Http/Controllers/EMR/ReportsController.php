<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticExam;
use App\Models\Exam;
use App\Models\History;
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

    public function getCountRows(): JsonResponse {
        $fechaActual    = now()->toDateString();
        $hc             = History::whereNull('deleted_at')->count();
        $ex             = Exam::whereNull('deleted_at')->count();
        //$ap             = Appointment::whereNull('deleted_at')->count();
        $cd             = History::whereNull('deleted_at')->whereDate('created_at', $fechaActual)->count();
        return response()->json(compact('hc', 'ex', 'cd'), 200);
    }

    public function years(){
        return History::selectRaw('YEAR(created_at) as year')->groupBy('year')->orderBy('year', 'desc')->get();
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
            $data[$record->month - 1] = $record->count;
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
        $histories = $this->getMonthlyCountsByYear($year, History::class, 'Historias');
        $exams = $this->getMonthlyCountsByYear($year, Exam::class, 'Exámenes');

        return array_merge($histories, $exams);
    }

    public function getDiagnosticsByExam(){
        return DiagnosticExam::selectRaw('d.descripcion as diagnostico, COUNT(examen_diagnostico.id_diagnostico) as cantidad')
            ->join('diagnosticos as d', 'd.id', '=', 'examen_diagnostico.id_diagnostico')
            ->whereNull('examen_diagnostico.deleted_at')
            ->groupBy('diagnostico')
            ->having('cantidad', '>', '0')
            ->orderBy('cantidad', 'desc')
            ->limit(15)
            ->get();
    }

    public function getDrugsByExam(){
        return MedicationExam::selectRaw('d.descripcion as droga, COUNT(*) as cantidad')
            ->join('drogas as d', 'd.id', '=', 'examen_medicacion.id_droga')
            ->whereNull('examen_medicacion.deleted_at')
            ->groupBy('droga')
            ->having('cantidad', '>', '0')
            ->orderBy('cantidad', 'desc')
            ->limit(15)
            ->get();
    }

    public function getHistoriesBySex(){
        return History::selectRaw('s.descripcion as sexo, COUNT(historias.id_sexo) as cantidad')
            ->join('sexo as s', 's.id', '=', 'historias.id_sexo')
            ->groupBy('sexo')
            ->get();
    }

    public function getHistoriesByBloodingGroup(){
        return History::selectRaw('gs.descripcion grupo_sanguineo, COUNT(historias.id_gs) cantidad')
            ->join('grupo_sanguineo as gs', 'gs.id', '=', 'historias.id_gs')
            ->groupBy('grupo_sanguineo')
            ->orderBy('cantidad', 'desc')
            ->get();
    }

    public function getHistoriesByMaritalStatus(){
        return History::selectRaw('e.descripcion estado_civil, COUNT(historias.id_estado) cantidad')
            ->join('estado_civil as e', 'e.id', '=', 'historias.id_estado')
            ->groupBy('estado_civil')
            ->orderBy('cantidad', 'desc')
            ->get();
    }

    public function getHistoriesByDegreeIntruction(){
        return History::selectRaw('di.descripcion grado_instruccion, COUNT(historias.id_gi) cantidad')
            ->join('grado_instruccion as di', 'di.id', '=', 'historias.id_gi')
            ->groupBy('grado_instruccion')
            ->orderBy('cantidad', 'desc')
            ->get();
    }

    public function getHistoriesBySmoking(){
        return Exam::selectRaw('t.consumo tabaquismo, COUNT(historias.id_ct) cantidad')
            ->join('tabaquismo as t', 't.id', '=', 'historias.id_ct')
            ->groupBy('t.consumo')
            ->orderBy('cantidad', 'desc')
            ->get();
    }
}
