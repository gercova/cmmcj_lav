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

class DiagnosticsController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
    }

    public function index(): View {
        return view('maintenance.diagnostics.index');
    }

    public function store(DiagnosticValidate $request): JsonResponse {
        $validated      = $request->validated();
        $proccessData   = [
            'codigo'        => $this->generateCode(),
            'descripcion'   => strtoupper($validated['descripcion']),
        ];

        $data = array_merge($validated, $proccessData);

        DB::beginTransaction();
        try {
            $result = Diagnosis::updateOrCreate(['id' => $request->id], $data);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $result->wasChanged() ? 'Diagnóstico actualizado correctamente' : 'Diagnóstico guardado correctamente',
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
        return DiagnosisResource::make($diagnosis);
    }

    public function search (Request $request) {
        $diagnostics = Diagnosis::where('descripcion', 'like', '%'.$request->query.'%')
            ->orWhere('codigo', 'like', '%'.$request->query.'%')
            ->limit(5)
            ->get();
        return DiagnosisResource::collection($diagnostics);
    }

    public function generateCode(): string {
        $newCode = strtoupper(Str::random(4));
        $exists = Diagnosis::where('code', $newCode)->count();
        if ($exists > 1) {
            $newCode = $newCode . '1';
        }
        return $newCode;
    }

    public function destroy(Diagnosis $diagnosis) {
        $diagnosis->delete();
        return response()->json([
            'success' => true,
            'message' => 'Diagnóstico eliminado correctamente',
        ], 200);
    }
}
