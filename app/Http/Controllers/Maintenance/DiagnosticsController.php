<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiagnosticValidate;
use App\Http\Resources\DiagnosisResource;
use App\Models\Diagnosis;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DiagnosticsController extends Controller {
    
    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:diagnostico_acceder')->only('index');
		$this->middleware('permission:diagnostico_ver')->only('see', 'list', 'show', 'search');
        $this->middleware('permission:diagnostico_guardar')->only('store');
		$this->middleware('permission:diagnostico_borrar')->only('destroy');
    }

    public function index(): View {
        return view('maintenance.diagnostics.index');
    }

    public function list(Request $request): JsonResponse {

		$startIndex = $request->input('jtStartIndex', 0);
		$pageSize 	= $request->input('jtPageSize', 10);
		$itemSearch = $request->input('search');
		// Obtener los datos filtrados
		list($data, $count) = Diagnosis::getAllDiagnostics($startIndex, $pageSize, $itemSearch);
		// Agregar permisos al resultado para el frontend
        $permissions = [];

		$data = $data->map(function ($record) use ($permissions) {
			$record->Permissions = $permissions;
			return $record;
		});
	
		$jTableResult = [
			'Result'            => 'OK',
			'Records'           => $data,
			'TotalRecordCount'  => $count,
		];
	
		return response()->json($jTableResult);
	}

    public function store(DiagnosticValidate $request): JsonResponse {
        $validated      = $request->validated();
        $proccessData   = [
            'codigo'        => isset($request->codigo) ? $request->codigo : $this->generateCode(),
            'descripcion'   => strtoupper($validated['descripcion']),
        ];

        $data = array_merge($validated, $proccessData);

        DB::beginTransaction();
        try {
            $result = Diagnosis::updateOrCreate(['id' => $request->id], $data);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Diagnóstico actualizado correctamente' : 'Diagnóstico guardado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el diagnóstico',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function show(Diagnosis $diagnosis) {
        return response()->json($diagnosis);
    }

    public function search (Request $request) {
        $diagnostics = Diagnosis::where('descripcion', 'like', '%'.$request->input('q').'%')
            ->orWhere('codigo', 'like', '%'.$request->input('q').'%')
            ->limit(5)
            ->get();
        return DiagnosisResource::collection($diagnostics);
    }

    public function generateCode(): string {
        $newCode = strtoupper(Str::random(4));
        $exists = Diagnosis::where('codigo', $newCode)->count();
        if ($exists > 1) {
            $newCode = $newCode . '1';
        }
        return $newCode;
    }

    public function destroy(Diagnosis $diagnosis): JsonResponse {
        $diagnosis->delete();
        return response()->json([
            'status'    => true,
            'type'      => 'success',
            'message'   => 'Diagnóstico eliminado correctamente',
        ], 200);
    }
}
