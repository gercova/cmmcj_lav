<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamValidate;
use App\Models\ContraceptiveMethod;
use App\Models\DiagnosticExam;
use App\Models\DocumentExam;
use App\Models\Enterprise;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\History;
use App\Models\MedicationExam;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ExamsController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:examen_acceder')->only('index');
		$this->middleware('permission:examen_ver')->only('see', 'view', 'listExams', 'listDiagnostics', 'listMedications');
		$this->middleware('permission:examen_crear')->only('new');
		$this->middleware('permission:examen_editar')->only('edit');
        $this->middleware('permission:examen_guardar')->only('store');
		$this->middleware('permission:examen_borrar')->only('destroy', 'destroyDiagnostics', 'destroyMedications', 'destroyDocument');
    }

    public function index(): View {
        return view('emr.exams.index');
    }

    public function new(History $history): View {
        $te     = ExamType::all();
        $mac    = ContraceptiveMethod::all();
        return view('emr.exams.new', compact('history', 'te', 'mac'));
    }

    public function edit(Exam $exam): View {
        $te     = ExamType::all();
        $hc     = DB::select('CALL PA_getMedicalHistoryByExam(?)', [$exam->id]);
        $mac    = ContraceptiveMethod::all();
        return view('emr.exams.edit', compact('exam', 'te', 'hc', 'mac'));
    }

    public function see(History $history): View {
        return view('emr.exams.see', compact('history'));
    }

    public function view(Exam $exam): JsonResponse {
        $hc			= History::findOrFail($exam->historia_id);
		$diagnostic = DB::select('CALL PA_getDiagnosticsByExam(?)', [$exam->id]);
		$medication = DB::select('CALL PA_getMedicationByExam(?)', [$exam->id]);
		return response()->json(compact('exam', 'hc', 'diagnostic', 'medication'), 200);
	}

    public function listExams(History $history): JsonResponse {
        $results 		= DB::select('CALL PA_getExamsbyMedicalHistory(?)', [$history->id]);
		$data 			= collect($results)->map(function ($item, $index) {
			$user   	= auth()->user();
			$buttons 	= '';
			if($user->can('examen_ver')){
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-info view-exam btn-xs" value="%s"><i class="bi bi-eye"></i> Ver receta</button>&nbsp;',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }
			if($user->can('examen_editar')){
                $buttons .= sprintf(
                    '<a type="button" class="btn btn-warning btn-xs" href="%s"><i class="bi bi-pencil-square"></i> Editar</a>&nbsp;',
                    htmlspecialchars(route('emr.exams.edit', ['exam' => $item->id]), ENT_QUOTES, 'UTF-8'),
                );
            }
			if($user->can('examen_borrar')){
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-danger delete-exam btn-xs" value="%s"><i class="bi bi-trash"></i> Eliminar</button>',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }

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

    public function listDiagnostics(Exam $exam): JsonResponse {
        $results 		= DB::select('CALL PA_getDiagnosticsByExam(?)', [$exam->id]);
		$data 			= collect($results)->map(function ($item, $index) {
			$user 		= auth()->user();
			$buttons 	= '';
			if($user->can('examen_borrar')){
				$buttons .= sprintf(
					'<button type="button" class="btn btn-danger delete-diagnostic btn-xs" value="%s"><i class="bi bi-trash"></i></button>',
					$item->id,
				);
			}
			return [
                $item->codigo,
				$item->diagnostico,
				$buttons ?: '<span class="text-muted">No autorizado</span>',
			];
		});

		return response()->json([
			"sEcho"					=> 1,
			"iTotalRecords"			=> $data->count(),
			"iTotalDisplayRecords"	=> $data->count(),
			"aaData"				=> $data ?? [],
		], 200);
    }

    public function listDocuments(Exam $exam): JsonResponse {
        $results 		= DB::select('CALL PA_getDocumentsByExam(?)', [$exam->id]);
		$data 			= collect($results)->map(function ($item, $index) {
            $user 		= auth()->user();
			$buttons 	= '';
            $file       = '<a href="'. asset('storage/'.$item->documento) .'" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Ver archivo</a>';
			if($user->can('examen_borrar')){
				$buttons .= sprintf(
					'<button type="button" class="btn btn-danger delete-doc btn-xs" value="%s"><i class="bi bi-trash"></i> Eliminar</button>',
					$item->id
				);
			}
			return [
                $item->nombre_examen,
                $file,
				$item->fecha_examen,
				$item->created_at,
                $buttons ?: '<span class="text-muted">No autorizado</span>',
			];
		});

		return response()->json([
			"sEcho"					=> 1,
			"iTotalRecords"			=> $data->count(),
			"iTotalDisplayRecords"	=> $data->count(),
			"aaData"				=> $data ?? [],
		], 200);
    }

    public function validateMatchDx (Request $request): JsonResponse {
        $request->validate([
            'examId'        => 'required|integer',
            'diagnosticId'  => 'required|integer',
        ]);

        $exists = DiagnosticExam::where('examen_id', $request->examId)->where('diagnostico_id', $request->diagnosticId)->exists();

        return response()->json([
            'status'    => $exists,
            'message'   => $exists ? 'El diagnóstico ya se encuentra en la lista' : 'Puede agregarlo',
            'type'      => $exists ? 'warning' : 'success',
        ]);
    }

    public function ValidateMatchMx (Request $request): JsonResponse {
        $request->validate([
            'examId'    => 'required|integer',
            'drugId'    => 'required|integer',
        ]);

        $exists = MedicationExam::where('examen_id', $request->examId)->where('farmaco_id', $request->drugId)->exists();
        return response()->json([
            'status'    => $exists,
            'message'   => $exists ? 'El fármaco ya está en la lista' : 'Puede agregarlo',
            'type'      => $exists ? 'warning' : 'success',
        ], 200);
    }

    public function listMedications(Exam $exam): JsonResponse {
        $results 		= DB::select('CALL PA_getMedicationByExam(?)', [$exam->id]);
		$data 			= collect($results)->map(function ($item, $index) {
			$user 		= auth()->user();
			$buttons 	= '';
			//if($user->can('examen_borrar')){
				$buttons .= sprintf(
					'<button type="button" class="btn btn-danger delete-drug btn-xs" value="%s"><i class="bi bi-trash"></i></button>',
					$item->id
				);
			//}
			return [
				// $index + 1,
				$item->drug,
				$item->rp,
                $item->dosis,
				$buttons ?: '<span class="text-muted">No autorizado</span>',
			];
		});

		return response()->json([
			"sEcho"					=> 1,
			"iTotalRecords"			=> $data->count(),
			"iTotalDisplayRecords"	=> $data->count(),
			"aaData"				=> $data ?? [],
		], 200);
    }

    public function store(ExamValidate $request): JsonResponse {
        $validated      = $request->validated();
        $diagnostics 	= $request->input('diagnostic_id');
		$drugs 			= $request->input('drug_id');
		$descriptions 	= $request->input('description');
        $dosis          = $request->input('dosis');
        $nombreDoc      = $request->input('nombre_examen');
		$document 		= $request->file('documento');
		$dateDoc 	    = $request->input('fecha_examen');
        $id 			= $request->input('id');

        DB::beginTransaction();
        try {
            $result     = Exam::updateOrCreate(['id' => $request->input('id')], $validated);
            $history    = History::find($request->input('historia_id'));
            // Guardar diagnóstico, medicación y subir documentos si existen
            if ($diagnostics) 	$this->saveDiagnosis($result->id, $result->historia_id, $diagnostics);
            if ($drugs) 		$this->saveMedicacion($result->id, $result->historia_id, $drugs, $descriptions, $dosis);
            if ($document)      $this->saveDocument($document, $result->historia_id, $history->dni, $nombreDoc, $dateDoc, $id);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Examen actualizado correctamente' : 'Examen guardado correctamente',
                'route'     => route('emr.exams.see', $result->historia_id),
                'print_a4' 	=> route('emr.exams.print', [$result->id, 'a4']),
				'print_a5' 	=> route('emr.exams.print', [$result->id, 'a5']),
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => 'Error al guardar el examen',
                'errors'    => $th->getMessage(),
            ], 500);
        }
    }

    private function saveDiagnosis($id, $history, $diagnosticId): void {
        if (empty($diagnosticId)) return;

        $data = collect($diagnosticId)->map(fn ($diagnosticId) => [
            'examen_id'         => $id,
            'historia_id'       => $history,
            'diagnostico_id'    => $diagnosticId,
            'created_at'        => now(),
            'updated_at'        => now(),
        ])->toArray();

        DiagnosticExam::insert($data);
    }

    private function saveMedicacion($id, $history, $drugId, $description, $dosis): void {
        if (empty($drugId)) return;

        $data = [];
		for ($i = 0; $i < count($drugId); $i++) {
			$data[] = [
				'examen_id'     => $id,
				'historia_id'   => $history,
				'farmaco_id' 	=> $drugId[$i],
				'descripcion'   => $description[$i],
                'dosis'         => $dosis[$i],
                'created_at'    => now(),
                'created_at'    => now(),
			];
		}

        MedicationExam::insert($data);
    }

	/*private function saveDocument($documents, $history, $dni, $nombreDocumento, $fechaDocument, $id) {
        if ($documents) {
            $directorio = "img/pacientes/{$dni}";
			if (!Storage::exists($directorio)) {
                Storage::makeDirectory($directorio);
            }
			# Storage::disk('public')->makeDirectory($directorio);
			foreach ($documents as $i => $document) {
				if ($document->isValid()) {
					//$extension 	= $document->extension();
					$extension 	= $document->getClientOriginalExtension();
					$fileName 	= mt_rand(1000, 9999).'.'.$extension;
					$route 		= "{$directorio}/{$fileName}";

					if ($document->storeAs($directorio, $fileName, 'public')) {
						DocumentExam::create([
							'examen_id'         => $id,
							'historia_id'       => $history,
							'nombre_examen'     => $nombreDocumento[$i],
							'documento'         => $route,
                            'fecha_documento'   => $fechaDocument[$i],
						]);
					}
				}
			}
        }
    }*/

    private function saveDocument($documents, $history, $dni, $nombreDocumento, $fechaDocument, $id): void {
        // Validar que se recibieron documentos
        if (!$documents || !is_array($documents)) return;
        // Definir tipos de archivos permitidos
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-word.document.macroEnabled.12',
        ];
        // Directorio base
        $directorio = "pacientes/{$dni}";
        // Crear directorio si no existe
        if (!Storage::disk('public')->exists($directorio)) {
            Storage::disk('public')->makeDirectory($directorio);
        }
        // Procesar cada documento
        foreach ($documents as $index => $document) {
            // Validar que el archivo es válido
            if (!$document || !$document->isValid()) continue;
            // Validar tipo MIME
            $mimeType = $document->getMimeType();
            if (!in_array($mimeType, $allowedMimeTypes)) {
                Log::warning("Tipo de archivo no permitido: {$mimeType} - Archivo: {$document->getClientOriginalName()}");
                continue; // Saltar archivo no permitido
            }
            // Generar nombre único seguro
            $extension = $document->getClientOriginalExtension();
            $fileName = uniqid('doc_', true) . '.' . $extension; // Más seguro que mt_rand()
            // Ruta relativa para guardar en DB (NO la ruta absoluta)
            $relativePath = "{$directorio}/{$fileName}";
            try {
                // Subir el archivo al disco público
                $uploaded = $document->storeAs($directorio, $fileName, 'public');
                if (!$uploaded) {
                    Log::error("Falló la subida del archivo: {$document->getClientOriginalName()}");
                    continue;
                }
                // Guardar en la base de datos
                DocumentExam::create([
                    'examen_id'         => $id,
                    'historia_id'       => $history,
                    'nombre_examen'     => $nombreDocumento[$index] ?? 'Documento sin nombre',
                    'documento'         => $relativePath, // Solo la ruta relativa
                    'fecha_examen'      => $fechaDocument[$index] ?? now()->toDateString(),
                ]);
                Log::info("Archivo subido exitosamente: {$fileName}");
            } catch (\Exception $e) {
                Log::error("Error al subir archivo: {$e->getMessage()} - Archivo: {$document->getClientOriginalName()}");
                continue;
            }
        }
    }

    public function destroy(Exam $exam): JsonResponse {
        $exam->delete();
        return response()->json([
            'status'    => (bool) $exam,
            'type'      => $exam ? 'success' : 'error',
            'message'   => $exam ? 'Examen eliminado' : 'Error al eliminar el examen'
        ],200);
    }

    public function destroyDiagnostics(DiagnosticExam $dx): JsonResponse {
        $dx->delete();
        return response()->json([
            'status'    => (bool) $dx,
            'type'      => $dx ? 'success' : 'error',
            'message'   => $dx ? 'Diagnostico eliminado': 'Error al eliminar el diagnóstico'
        ], $dx ? 200 : 400);
    }

    public function destroyMedications(MedicationExam $mx): JsonResponse {
        $mx->delete();
        return response()->json([
            'status'    => (bool) $mx,
            'type'      => $mx ? 'succes' : 'error',
            'message'   => $mx ? 'Medicación eliminada' : 'Error al eliminar medicación'
        ], $mx ? 200 : 400);
    }

    public function destroyDocument(DocumentExam $doc): JsonResponse {
        $doc->delete();
        return response()->json([
            'status'    => (bool) $doc,
            'type'      => $doc ? 'success' : 'error',
            'message'   => $doc ? 'Documento eliminado' : 'Error al eliminar el documento'
        ], $doc ? 200 : 400);
    }

    public function printPrescriptionId(int $id, string $format = 'a5') {
        // Validar y normalizar formato
        $format     = in_array($format, ['a4', 'a5']) ? $format : 'a5';
        // Obtener datos de manera más eficiente
        $exam       = Exam::findOrFail($id);
        $user       = Auth::user();
        $enterprise = Enterprise::findOrFail(1);
        // Ejecutar procedimientos almacenados de forma más limpia
        $medicalHistory = DB::select('CALL PA_getMedicalHistoryByExam(?)', [$id]);
        $diagnostics    = DB::select('CALL PA_getDiagnosticsByExam(?)', [$id]);
        $medications    = DB::select('CALL PA_getMedicationByExam(?)', [$id]);
        // Configurar PDF según formato
        $pdf = $this->configurePdf($format, [
            'hc' => $medicalHistory,
            'ex' => $exam,
            'dx' => $diagnostics,
            'mx' => $medications,
            'us' => $user,
            'en' => $enterprise,
            'format' => $format
        ]);

        $filename = "receta-medica-examen-{$id}-" . strtoupper($format) . ".pdf";
        
        return $pdf->stream($filename);
    }

    /**
     * Configura el PDF según el formato especificado
     */
    private function configurePdf(string $format, array $data) {
        $view   = $format === 'a4' ? 'EMR.exams.pdf-a4' : 'EMR.exams.pdf-a5';
        $pdf    = PDF::loadView($view, $data);
        $config = $this->getPdfConfig($format);
        $pdf->setPaper($config['paper'], 'portrait')->setOptions($config['options']);
        return $pdf;
    }

    /**
     * Obtiene la configuración del PDF según el formato
     */
    private function getPdfConfig(string $format): array {
        $baseOptions = [
            'fontDefault'           => 'sans-serif',
            'isHtml5ParserEnabled'  => true,
            'isRemoteEnabled'       => false,
            'isPhpEnabled'          => false,
            'chroot'                => realpath(base_path()),
        ];

        if ($format === 'a4') {
            return [
                'paper'     => 'a4',
                'options'   => array_merge($baseOptions, [
                    'margin_top'    => 10,
                    'margin_bottom' => 10,
                    'margin_left'   => 15,
                    'margin_right'  => 15,
                ])
            ];
        }

        return [
            'paper'     => 'a5',
            'options'   => array_merge($baseOptions, [
                'margin_top'    => 0.5,
                'margin_bottom' => 0.5,
                'margin_left'   => 0.5,
                'margin_right'  => 0.5,
            ])
        ];
    }
}
