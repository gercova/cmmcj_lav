<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HistoryValidate;
use App\Models\BloodGroup;
use App\Models\DegreesInstruction;
use App\Models\DocumentType;
use App\Models\History;
use App\Models\Insurance;
use App\Models\MaritalStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoriesController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
    }

    public function index(): View {
        return view('EMR.histories.index');
    }

    public function new(): View {
        $td = DocumentType::all();
        $gs = BloodGroup::all();
        $gi = DegreesInstruction::all();
        $ec = MaritalStatus::all();
        $ss = Insurance::all();
        return view('EMR.histories.new', compact('td', 'gs', 'gi', 'ec', 'ss'));
    }

    public function edit(History $history): View {
        $td = DocumentType::all();
        $gs = BloodGroup::all();
        $gi = DegreesInstruction::all();
        $ec = MaritalStatus::all();
        $ss = Insurance::all();
        return view('EMR.histories.edit', compact('history', 'td', 'gs', 'gi', 'ec', 'ss'));
    }

    public function store(HistoryValidate $request): JsonResponse {
        $validated = $request->validated();

        $proccessFields = [
            'tipo_documento_id' => $validated['tipo_documento_id'],
            'dni'               => $validated['dni'],
            'nombres'           => strtoupper($validated['nombres']),
            'apellidos'         => strtoupper($validated['apellidos']),
            //'sexo'              => strtoupper($validated['sexo']),
            'fecha_nacimiento'  => $validated['fecha_nacimiento'],
            'telefono'          => $validated['telefono'],
            'email'             => $validated['email'],
            //'direccion'         => strtoupper($validated['direccion']),
            'ubigeo_nacimiento' => $this->getStringId($validated['ubigeo_nacimiento']),
            'ubigeo_residencia' => $this->getStringId($validated['ubigeo_residencia']),
            'ocupacion_id'      => $this->getStringId($validated['ocupacion_id']),
        ];

        $data = array_merge($proccessFields);

        DB::beginTransaction();
        try {
            $result = History::updateOrCreate(['id' => $request->input('id')], $data);

            return response()->json([
                'status'    => (bool) $result,
                'type'      => $result ? 'success' : 'error',
                'message'   => $result->wasRecentlyCreated ? 'Historia clínica guardada correctamente' : 'Historia clínica actualizada correctamente',
                'route'     => route('emr.histories.edit', $result->id),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => $th->getMessage(),
            ]);
        }
    }

    public function getStringId($obj){
		$value = explode(" | ", $obj);
		return $value[0];
	}

    public function destroy(History $history): JsonResponse {
        $history->delete();
        return response()->json([
            'status'    => (bool) $history,
            'type'      => $history ? 'success' : 'error',
            'message'   => $history ? 'Historia clínica eliminada correctamente' : 'Error al eliminar la historia clínica',
        ]);
    }
}
