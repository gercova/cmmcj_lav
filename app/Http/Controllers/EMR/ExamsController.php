<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\BloodTestValidate;
use App\Http\Requests\ExamValidate;
use App\Http\Requests\StoolTestValidate;
use App\Http\Requests\UrineTestValidate;
use App\Http\Resources\BloodTestResource;
use App\Http\Resources\ExamResource;
use App\Http\Resources\StoolTestResource;
use App\Http\Resources\UrineTestResource;
use App\Models\BloodTest;
use App\Models\ContraceptiveMethod;
use App\Models\DiagnosticExam;
use App\Models\DocumentExam;
use App\Models\Enterprise;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\History;
use App\Models\MedicationExam;
use App\Models\StoolTest;
use App\Models\UrineTest;
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

    public function viewDetailExam(Exam $exam): jsonResponse {
        $exam->load(['historia']);
		return response()->json(ExamResource::make($exam), 200);
    }

    public function showBloodTest(BloodTest $bt): JsonResponse {
        $bt->load(['examen.historia']);
        return response()->json(BloodTestResource::make($bt), 200);
    }

    public function showUrineTest(UrineTest $ut): JsonResponse {
        $ut->load(['examen.historia']);
        return response()->json(UrineTestResource::make($ut), 200);
    }

    public function showStoolTest(StoolTest $st): JsonResponse {
        $st->load(['examen.historia']);
        return response()->json(StoolTestResource::make($st), 200);
    }

    public function listExams(History $history): JsonResponse {
        $results    = DB::select('CALL PA_getExamsbyMedicalHistory(?)', [$history->id]);
        $user       = auth()->user();
        $canCreate  = $user->can('examen_crear');
        $canSee     = $user->can('examen_ver');
        $canEdit    = $user->can('examen_editar');
        $canDelete  = $user->can('examen_borrar');
        $data       = collect($results)->map(function ($item, $index) use ($canCreate, $canSee, $canEdit, $canDelete) {
            return [
                $index + 1,
                $item->created_at,
                $item->descripcion,
                $this->buildActionDropdown($item->id, $canCreate, $canSee, $canEdit, $canDelete),
            ];
        });

        return response()->json([
            "sEcho"                 => 1,
            "iTotalRecords"         => $data->count(),
            "iTotalDisplayRecords"  => $data->count(),
            "aaData"                => $data ?? [],
        ], 200);
    }

    protected function buildActionDropdown($id, $canCreate, $canSee, $canEdit, $canDelete): string {
        $buttons = [];
        $buttonsExtra = [];

        if (!$canCreate && !$canSee && !$canEdit && !$canDelete) return '<span class="text-muted">Sin acciones</span>';
        // Botones para crear (van en el dropdown)
        if ($canCreate) {
            $buttonsExtra[] = '<li><a class="dropdown-item" id="btnAddBloodTest" type="button" value="'.e($id).'"><i class="bi bi-plus-square"></i> Crear examen de sangre</a></li>';
            $buttonsExtra[] = '<li><a class="dropdown-item" id="btnAddUrineTest" type="button" value="'.e($id).'"><i class="bi bi-plus-square"></i> Crear examen de orina</a></li>';
            $buttonsExtra[] = '<li><a class="dropdown-item" id="btnAddStoolTest" type="button" value="'.e($id).'"><i class="bi bi-plus-square"></i> Crear examen de heces </a></li>';
        }
        // Botones normales (fuera del dropdown)
        if ($canSee) {
            $buttons[] = sprintf(
                '<button type="button" class="btn btn-info view-exam btn-xs" value="%s"> Ver detalle</button>',
                htmlspecialchars($id, ENT_QUOTES, 'UTF-8')
            );
        }

        if ($canEdit) {
            $buttons[] = sprintf(
                '<a type="button" class="btn btn-warning btn-xs" href="%s"><i class="bi bi-pencil-square"></i> Editar</a>',
                htmlspecialchars(route('emr.exams.edit', ['exam' => $id]), ENT_QUOTES, 'UTF-8')
            );
        }

        if ($canDelete) {
            $buttons[] = sprintf(
                '<button type="button" class="btn btn-danger delete-exam btn-xs" value="%s"><i class="bi bi-trash"></i> Eliminar</button>&nbsp;',
                htmlspecialchars($id, ENT_QUOTES, 'UTF-8')
            );
        }
        // Construir el HTML final
        $html = implode(' ', $buttons);
        // Si hay botones para el dropdown, agregar el grupo de dropdown
        if (!empty($buttonsExtra)) {
            $html .= '<div class="btn-group">
                <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">Acciones</button>
                <ul class="dropdown-menu">'.implode('', $buttonsExtra).'</ul>
            </div>';
        }

        return $html;
    }

    public function listBloodTests(History $history): JsonResponse {
        $user       = auth()->user();
        $canEdit    = $user->can('examen_editar');
        $canDelete  = $user->can('examen_borrar');

        // Filtrar solo exámenes que tienen examen de sangre
        $results = Exam::with(['examenSangre' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->where('historia_id', $history->id)
            ->whereHas('examenSangre') // ¡ESTA ES LA CLAVE!
            ->get();

        $data = $results->map(function($item, $key) use ($canEdit, $canDelete) {
            // Verificar adicionalmente que tenga examenes de sangre
            if ($item->examenSangre->isEmpty()) return null; // Saltar este item

            $list = '<table class="table table-sm">';
            foreach ($item->examenSangre as $e) {
                // Botones para cada examen de sangre con permisos
                $actionButtons = '';
                $actionButtons .= sprintf(
                    '<a href="%s" class="btn btn-sm btn-info btn-sm" target="_blank"><i class="bi bi-file-earmark-pdf"></i></a>&nbsp;',
                    htmlspecialchars(route('emr.exams.print-bt', ['bt' => $e->id]), ENT_QUOTES, 'UTF-8')
                );

                if ($canEdit) {
                    $actionButtons .= sprintf(
                        '<button class="btn btn-sm btn-warning update-row-bt btn-sm" value="%s"><i class="bi bi-pencil-square"></i></button>&nbsp;',
                        $e->id
                    );
                }

                if ($canDelete) {
                    $actionButtons .= sprintf(
                        '<button class="btn btn-sm btn-danger delete-bt btn-sm" value="%s"><i class="bi bi-trash"></i></button>',
                        $e->id
                    );
                }

                $list .= sprintf(
                    '<tr><td><span class="badge badge-success">%s</span></td><td>%s</td></tr>',
                    $e->created_at->format('Y-m-d H:i:s'),
                    $actionButtons
                );
            }

            $list .= '</table>';

            return [
                $key + 1,
                $list,
                $item->tipoExamen->descripcion,
                $item->created_at->format('Y-m-d H:i:s')
            ];
        })->filter(); // Filtrar elementos null

        return response()->json([
            "sEcho"                 => 1,
            "iTotalRecords"         => $data->count(),
            "iTotalDisplayRecords"  => $data->count(),
            "aaData"                => $data->values() ?? [], // reindexar
        ], 200);
    }

    public function listUrineTests(History $history): JsonResponse {
        $user       = auth()->user();
        $canEdit    = $user->can('examen_editar');
        $canDelete  = $user->can('examen_borrar');

        // Filtrar solo exámenes que tienen examen de sangre
        $results = Exam::with(['examenOrina' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->where('historia_id', $history->id)
            ->whereHas('examenOrina') // ¡ESTA ES LA CLAVE!
            ->get();

        $data = $results->map(function($item, $key) use ($canEdit, $canDelete) {
            // Verificar adicionalmente que tenga examenes de sangre
            if ($item->examenOrina->isEmpty()) return null; // Saltar este item

            $list = '<table class="table table-sm">';
            foreach ($item->examenOrina as $e) {
                // Botones para cada examen de sangre con permisos
                $actionButtons = '';
                $actionButtons .= sprintf(
                    '<a href="%s" class="btn btn-sm btn-info btn-sm" target="_blank"><i class="bi bi-file-earmark-pdf"></i></a>&nbsp;',
                    htmlspecialchars(route('emr.exams.print-ut', ['ut' => $e->id]), ENT_QUOTES, 'UTF-8')
                );

                if ($canEdit) {
                    $actionButtons .= sprintf(
                        '<button class="btn btn-sm btn-warning update-row-ut btn-sm" value="%s"><i class="bi bi-pencil-square"></i></button>&nbsp;',
                        $e->id
                    );
                }

                if ($canDelete) {
                    $actionButtons .= sprintf(
                        '<button class="btn btn-sm btn-danger delete-ut btn-sm" value="%s"><i class="bi bi-trash"></i></button>',
                        $e->id
                    );
                }

                $list .= sprintf(
                    '<tr>
                        <td><span class="badge badge-success">%s</span></td>
                        <td>%s</td>
                    </tr>',
                    $e->created_at->format('Y-m-d H:i:s'),
                    $actionButtons
                );
            }

            $list .= '</table>';

            return [
                $key + 1,
                $list,
                $item->tipoExamen->descripcion,
                $item->created_at->format('Y-m-d H:i:s')
            ];
        })->filter(); // Filtrar elementos null

        return response()->json([
            "sEcho"                 => 1,
            "iTotalRecords"         => $data->count(),
            "iTotalDisplayRecords"  => $data->count(),
            "aaData"                => $data->values() ?? [], // reindexar
        ], 200);
    }

    public function listStoolTests(History $history): JsonResponse {
        $user       = auth()->user();
        $canEdit    = $user->can('examen_editar');
        $canDelete  = $user->can('examen_borrar');

        // Filtrar solo exámenes que tienen examen de sangre
        $results = Exam::with(['examenHeces' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->where('historia_id', $history->id)
            ->whereHas('examenHeces') // ¡ESTA ES LA CLAVE!
            ->get();

        $data = $results->map(function($item, $key) use ($canEdit, $canDelete) {
            // Verificar adicionalmente que tenga examenes de sangre
            if ($item->examenHeces->isEmpty()) return null; // Saltar este item

            $list = '<table class="table table-sm">';
            foreach ($item->examenHeces as $e) {
                // Botones para cada examen de sangre con permisos
                $actionButtons = '';
                $actionButtons .= sprintf(
                    '<a href="%s" class="btn btn-sm btn-info btn-sm" target="_blank"><i class="bi bi-file-earmark-pdf"></i></a>&nbsp;',
                    htmlspecialchars(route('emr.exams.print-st', ['st' => $e->id]), ENT_QUOTES, 'UTF-8')
                );

                if ($canEdit) {
                    $actionButtons .= sprintf(
                        '<button class="btn btn-sm btn-warning update-row-st btn-sm" value="%s"><i class="bi bi-pencil-square"></i></button>&nbsp;',
                        $e->id
                    );
                }

                if ($canDelete) {
                    $actionButtons .= sprintf(
                        '<button class="btn btn-sm btn-danger delete-st btn-sm" value="%s"><i class="bi bi-trash"></i></button>',
                        $e->id
                    );
                }

                $list .= sprintf(
                    '<tr><td><span class="badge badge-success">%s</span></td><td>%s</td></tr>',
                    $e->created_at->format('Y-m-d H:i:s'),
                    $actionButtons
                );
            }

            $list .= '</table>';

            return [
                $key + 1,
                $list,
                $item->tipoExamen->descripcion,
                $item->created_at->format('Y-m-d H:i:s')
            ];
        })->filter(); // Filtrar elementos null

        return response()->json([
            "sEcho"                 => 1,
            "iTotalRecords"         => $data->count(),
            "iTotalDisplayRecords"  => $data->count(),
            "aaData"                => $data->values() ?? [], // reindexar
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

    public function validateMatchDx(Request $request): JsonResponse {
        // 1. Cambiamos 'required' por 'nullable' en examId
        $request->validate([
            'examId'       => 'nullable|integer',
            'diagnosticId' => 'required|integer',
        ]);

        if (!$request->examId) {
            return response()->json([
                'status'  => false,
                'message' => 'No se ha seleccionado un examen.',
                'type'    => 'info' // Un tipo neutral
            ]);
        }

        $exists = DiagnosticExam::where('examen_id', $request->examId)->where('diagnostico_id', $request->diagnosticId)->exists();
        return response()->json([
            'status'  => $exists,
            'message' => $exists ? 'El diagnóstico ya se encuentra en la lista' : 'Puede agregarlo',
            'type'    => $exists ? 'warning' : 'success',
        ]);
    }

    public function validateMatchMx (Request $request) {
        $request->validate([
            'examId'    => 'nullable|integer',
            'drugId'    => 'required|integer',
        ]);

        if (!$request->examId) {
            return response()->json([
                'status'  => false,
                'message' => 'No se ha seleccionado un examen.',
                'type'    => 'info' // Un tipo neutral
            ]);
        }

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
            if ($document)      $this->saveDocument($document, $result->historia_id, $history->dni, $nombreDoc, $dateDoc, $result->id);
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
                'updated_at'    => now(),
			];
		}

        MedicationExam::insert($data);
    }

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
            Storage::disk('public')->makeDirectory($directorio, 0755, true); // Crea directorios recursivamente
        }

        // Procesar cada documento
        foreach ($documents as $index => $document) {
            // Validar que el archivo es válido
            if (!$document || !$document->isValid()) {
                Log::warning("Archivo no válido en el índice: {$index}");
                continue;
            }
            // Validar tipo MIME
            $mimeType = $document->getMimeType();
            if (!in_array($mimeType, $allowedMimeTypes)) {
                Log::warning("Tipo de archivo no permitido: {$mimeType} - Archivo: {$document->getClientOriginalName()}");
                continue; // Saltar archivo no permitido
            }
            // Generar nombre único seguro
            $extension  = $document->getClientOriginalExtension();
            $fileName   = uniqid('doc_', true) . '.' . $extension;
            // Ruta relativa para guardar en DB
            $relativePath = "{$directorio}/{$fileName}";

            try {
                // Subir el archivo al disco público
                $uploaded = $document->storeAs($directorio, $fileName, 'public');
                if (!$uploaded) {
                    Log::error("Falló la subida del archivo: {$document->getClientOriginalName()}");
                    continue;
                }

                // Obtener el nombre y fecha del documento correspondiente
                $nombreExamen   = null;
                $fechaExamen    = null;

                // Verificar si $nombreDocumento y $fechaDocument son arrays y tienen el índice correspondiente
                if (is_array($nombreDocumento) && isset($nombreDocumento[$index])) {
                    $nombreExamen = $nombreDocumento[$index];
                } elseif (is_string($nombreDocumento)) {
                    // Si es un string único (para un solo documento), usarlo para todos
                    $nombreExamen = $nombreDocumento;
                }

                if (is_array($fechaDocument) && isset($fechaDocument[$index])) {
                    $fechaExamen = $fechaDocument[$index];
                } elseif (is_string($fechaDocument)) {
                    // Si es un string único (para un solo documento), usarlo para todos
                    $fechaExamen = $fechaDocument;
                }

                // Si no se pudo obtener nombre o fecha, usar valores por defecto
                if ($nombreExamen === null) {
                    $nombreExamen = 'Documento sin nombre';
                }
                if ($fechaExamen === null) {
                    $fechaExamen = now()->toDateString();
                }

                // Guardar en la base de datos
                DocumentExam::create([
                    'examen_id'         => $id,
                    'historia_id'       => $history,
                    'nombre_examen'     => $nombreExamen,
                    'documento'         => $relativePath, // Solo la ruta relativa
                    'fecha_examen'      => $fechaExamen,
                ]);

                Log::info("Archivo subido y registrado exitosamente: {$fileName}");
            } catch (\Exception $e) {
                Log::error("Error al subir o registrar archivo: {$e->getMessage()} - Archivo: {$document->getClientOriginalName()}");
                // Opcional: Eliminar el archivo si la DB falla
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
                continue;
            }
        }
    }

    /*private function saveDocument($documents, $history, $dni, $nombreDocumento, $fechaDocument, $id): void {
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
    }*/

    public function storeBloodTest(BloodTestValidate $request): JsonResponse {
        $validated = $request->validated();
        // Definir todos los campos decimales que necesitan procesamiento
        $decimalFields = [
            'hemoglobina', 'hematocrito', 'leucocitos', 'neutrofilos', 'linfocitos', 'monocitos', 'eosinofilos', 'basofilos', 'plaquetas', 'glucosa', 'urea', 'creatinina', 'acido_urico', 'colesterol_total', 'trigliceridos', 'transaminasas_got', 'transaminasas_gpt', 'bilirrubina_total', 'bilirrubina_directa', 'fosfatasa_alcalina', 'proteinas_totales', 'albumina', 'globulina', 'sodio', 'potasio', 'cloro', 'calcio', 'vsg', 'tiempo_protrombina', 'tpt'
        ];
        // Procesar todos los campos decimales en una sola operación
        $processedFields = array_map(
            fn($value) => empty($value) || $value === '' ? null : $value,
            array_intersect_key($validated, array_flip($decimalFields))
        );

        $data = array_merge($validated, $processedFields);

        DB::beginTransaction();
        try {
            $result = BloodTest::updateOrCreate(['id' => $request->input('examen_sangre_id')], $data);
            DB::commit();

            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasRecentlyCreated ? 'Examen guardado correctamente' : 'Examen actualizado correctamente',
                'route_print' => route('emr.exams.print-bt', $result->id),
            ], 200);

        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => $th->getMessage(),
            ], 400);
        }
    }

    public function storeUrineTest(UrineTestValidate $request): JsonResponse {
        $validated = $request->validated();
        // Limpiar campos decimales explícitamente
        $validated['densidad']  = ($validated['densidad'] === '') ? null : $validated['densidad'];
        $validated['ph']        = ($validated['ph'] === '') ? null : $validated['ph'];
        DB::beginTransaction();
        try {
            $result = UrineTest::updateOrCreate(['id' => $request->input('examen_orina_id')], $validated);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasRecentlyCreated ? 'Examen guardado correctamente' : 'Examen actualizado correctamente',
                'route_print' => route('emr.exams.print-ut', $result->id),
            ], 200);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => $th->getMessage(),
            ], 400);
        }
    }

    public function storeStoolTest(StoolTestValidate $request): JsonResponse {
        $validated = $request->validated();
        // Limpiar campos decimales explícitamente
        $validated['ph'] = ($validated['ph'] === '') ? null : $validated['ph'];
        DB::beginTransaction();
        try {
            $result = StoolTest::updateOrCreate(['id' => $request->input('examen_heces_id')], $validated);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasRecentlyCreated ? 'Examen guardado correctamente' : 'Examen actualizado correctamente',
                'route_print' => route('emr.exams.print-st', $result->id),
            ], 200);
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => $th->getMessage(),
            ], 400);
        }
    }

    public function destroy(Exam $exam): JsonResponse {
        $exam->delete();
        return response()->json([
            'status'    => (bool) $exam,
            'type'      => $exam ? 'success' : 'error',
            'message'   => $exam ? 'Examen eliminado' : 'Error al eliminar el examen'
        ], 200);
    }

    public function destroyBloodTest(BloodTest $bt): JsonResponse {
        $bt->delete();
        return response()->json([
            'status'    => (bool) $bt,
            'type'      => $bt ? 'success' : 'error',
            'message'   => $bt ? 'Examen eliminado' : 'Error al eliminar el examen'
        ], 200);
    }

    public function destroyUrineTest(UrineTest $ut): JsonResponse {
        $ut->delete();
        return response()->json([
            'status'    => (bool) $ut,
            'type'      => $ut ? 'success' : 'error',
            'message'   => $ut ? 'Examen eliminado' : 'Error al eliminar el examen'
        ], 200);
    }

    public function destroyStoolTest(StoolTest $st): JsonResponse {
        $st->delete();
        return response()->json([
            'status'    => (bool) $st,
            'type'      => $st ? 'success' : 'error',
            'message'   => $st ? 'Examen eliminado' : 'Error al eliminar el examen'
        ], 200);
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

    private function configurePdf(string $format, array $data) {
        $view   = $format === 'a4' ? 'emr.exams.pdf-a4' : 'emr.exams.pdf-a5';
        $pdf    = PDF::loadView($view, $data);
        $config = $this->getPdfConfig($format);
        $pdf->setPaper($config['paper'], 'portrait')->setOptions($config['options']);
        return $pdf;
    }

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

    public function printBloodTest(BloodTest $bt) {
        $en = Enterprise::findOrFail(1);
        // Configurar PDF según formato
        $pdf = PDF::loadView('emr.exams.pdf-bt', compact('bt', 'en'));
        $pdf->setPaper('a4', 'portrait')
            ->setOptions([
                'margin_top' 	        => 10,
                'margin_bottom'         => 10,
                'margin_left' 	        => 15,
                'margin_right' 	        => 15,
                'fontDefault'           => 'sans-serif',
                'isHtml5ParserEnabled'  => true,
                'isRemoteEnabled'       => false,
                'isPhpEnabled'          => false,
                'chroot'                => realpath(base_path()),
            ]);

        $filename = "examen-sangre-{$bt->id}-{$bt->created_at->format('Y-m-d')}.pdf";
        return $pdf->stream($filename);
    }

    public function printUrineTest(UrineTest $ut) {
        $en = Enterprise::findOrFail(1);
        // Configurar PDF según formato
        $pdf = PDF::loadView('emr.exams.pdf-ut', compact('ut', 'en'));
        $pdf->setPaper('a4', 'portrait')
            ->setOptions([
                'fontDefault'           => 'sans-serif',
                'isHtml5ParserEnabled'  => true,
                'isRemoteEnabled'       => false,
                'isPhpEnabled'          => false,
                'chroot'                => realpath(base_path()),
            ]);

        $filename = "examen-orina-{$ut->id}-{$ut->created_at->format('Y-m-d')}.pdf";
        return $pdf->stream($filename);
    }

    public function printStoolTest(StoolTest $st) {
        $en = Enterprise::findOrFail(1);
        // Configurar PDF según formato
        $pdf = PDF::loadView('emr.exams.pdf-st', compact('st', 'en'));
        $pdf->setPaper('a4', 'portrait')
            ->setOptions([
                'fontDefault'           => 'sans-serif',
                'isHtml5ParserEnabled'  => true,
                'isRemoteEnabled'       => false,
                'isPhpEnabled'          => false,
                'chroot'                => realpath(base_path()),
            ]);

        $filename = "examen-heces-{$st->id}-{$st->created_at->format('Y-m-d')}.pdf";
        return $pdf->stream($filename);
    }
}
