<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HistoryValidate;
use App\Http\Resources\HistoryResource;
use App\Models\Appointment;
use App\Models\BloodGroup;
use App\Models\DegreesInstruction;
use App\Models\DocumentType;
use App\Models\History;
use App\Models\Insurance;
use App\Models\MaritalStatus;
use App\Models\Occupation;
use App\Models\UbigeoDistrict;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class HistoriesController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:historia_acceder')->only('index');
		$this->middleware('permission:historia_ver')->only('list');
		$this->middleware('permission:historia_crear')->only('new');
		$this->middleware('permission:historia_editar')->only('edit');
        $this->middleware('permission:historia_guardar')->only('store');
		$this->middleware('permission:historia_borrar')->only('destroy');
    }

    public function index(): View {
        return view('emr.histories.index');
    }

    public function new(): View {
        $td = DocumentType::all();
        $gs = BloodGroup::all();
        $gi = DegreesInstruction::all();
        $ec = MaritalStatus::all();
        $ss = Insurance::all();
        return view('emr.histories.new', compact('td', 'gs', 'gi', 'ec', 'ss'));
    }

    public function edit(History $history): View {
        $td = DocumentType::all();
        $gs = BloodGroup::all();
        $gi = DegreesInstruction::all();
        $ec = MaritalStatus::all();
        $ss = Insurance::all();
        $un = History::getUBirthByHistoryId($history->id);
        $ur = History::getUResidenceByHistoryId($history->id);
        $oc = History::getOccupationByHistoryId($history->id);
        return view('emr.histories.edit', compact('history', 'td', 'gs', 'gi', 'ec', 'ss', 'un', 'ur', 'oc'));
    }

    public function list(Request $request): JsonResponse {
		$startIndex = $request->input('jtStartIndex', 0);
		$pageSize 	= $request->input('jtPageSize', 10);
		$itemSearch = $request->input('search');
		// Obtener los datos filtrados
		list($data, $count) = History::getAllHistories($startIndex, $pageSize, $itemSearch);
		// Agregar permisos al resultado para el frontend
        $permissions = [];
		$permissions = [
            'add_appx'  => auth()->user()->can('cita_crear'), 		        // añadir una nueva cita
			'update_hc' => auth()->user()->can('historia_editar'), 	        // actualizar una historia
			'delete_hc' => auth()->user()->can('historia_borrar'), 		    // borrar una historia
			'add_exm' 	=> auth()->user()->can('examen_crear'), 		    // añadir un nuevo examen
			'view_exm' 	=> auth()->user()->can('examen_ver'), 			    // ver exámenes de un paciente
            'add_hsp'   => auth()->user()->can('hospitalizacion_crear'),    // añadir una hospitalización
            'view_hsp'  => auth()->user()->can('hospitalizacion_ver'), 		// ver hospitalizaciones de un paciente
		];

		$data = $data->map(function ($record) use ($permissions) {
			$record->Permissions = $permissions; // Agregar permisos al registro
			return $record;
		});

		$jTableResult = [
			'Result'            => 'OK',
			'Records'           => $data,
			'TotalRecordCount'  => $count,
		];

		return response()->json($jTableResult);
	}

    public function show(History $history): JsonResponse {
        return response()->json(HistoryResource::make($history), 200);
    }

    public function store(HistoryValidate $request): JsonResponse {
        $validated      = $request->validated();
        $proccessFields = [
            'nombres'           => strtoupper($validated['nombres']),
            'ubigeo_nacimiento' => isset($validated['ubigeo_nacimiento']) ? ($this->getStringId($validated['ubigeo_nacimiento'])) : '220901',
            'ubigeo_residencia' => $this->getStringId($validated['ubigeo_residencia']),
            'ocupacion_id'      => $this->getStringId($validated['ocupacion_id']),
        ];

        $data = array_merge($validated, $proccessFields);
        DB::beginTransaction();
        try {
            $result = History::updateOrCreate(['id' => $request->input('id')], $data);
            if($result->wasRecentlyCreated) {
                Appointment::create([
                    'historia_id'       => $result->id,
                    'estado_cita_id'    => 1,
                    'user_id'           => auth()->user()->id,
                    'fecha'             => $result->created_at->format('Y-m-d'),
                    'hora'              => $result->created_at->format('H:i:s'),
                ]);
            }

            DB::commit();
            return response()->json([
                'status'    => (bool) $result,
                'type'      => $result ? 'success' : 'error',
                'message'   => $result->wasChanged() ? 'Historia clínica actualizada correctamente' : 'Historia clínica guardada correctamente',
                'route'     => route('emr.histories.home'),
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

    public function searchDni(Request $request) {
		$dni = $request->input('dni');
		$token = env('API_DECOLETA_DNI');
		try {
			$response = Http::withHeaders([
				'Authorization' => 'Bearer ' . $token,
				'Accept' => 'application/json',
			])->timeout(30)->get('https://api.decolecta.com/v1/reniec/dni?numero=' . $dni);

			return $response->body();

		} catch (\Exception $e) {
			return response()->json([
				'error' => 'Error en la consulta: ' . $e->getMessage()
			], 500);
		}
	}

    public function searchLocation(Request $request) {
        $request->validate([
            'q' => 'nullable|string|max:100'
        ]);

        $q = $request->input('q');

        if (empty(trim($q)) || strlen(trim($q)) < 2) {
            return response()->json([]);
        }

        $data = UbigeoDistrict::where(function ($query) use ($q) {
            $query->where('ur.nombre', 'like', "%{$q}%")
                ->orWhere('up.nombre', 'like', "%{$q}%")
                ->orWhere('ubigeo_distrito.nombre', 'like', "%{$q}%")
                ->orWhere('ubigeo_distrito.id', 'like', "%{$q}%");
        })
            ->selectRaw('ubigeo_distrito.id as id, CONCAT(ubigeo_distrito.id, " | ", ur.nombre, " | ", up.nombre, " | ", ubigeo_distrito.nombre) as ubigeo')
            ->join('ubigeo_provincia as up', 'ubigeo_distrito.provincia_id', '=', 'up.id')
            ->join('ubigeo_region as ur', 'ubigeo_distrito.region_id', '=', 'ur.id')
            ->limit(5)
            ->get()
            ->toArray();

        return response()->json($data);
    }

    public function searchOccupation(Request $request) {
        $request->validate([
            'q' => 'nullable|string|max:100'
        ]);

        $q = $request->input('q');

        if (empty(trim($q)) || strlen(trim($q)) < 2) {
            return response()->json([]);
        }

        $data   = Occupation::where(function ($query) use ($q){
            $query->where('descripcion', 'like', "%{$q}%");
        })
        ->selectRaw('CONCAT(id, " | ", descripcion) ocupacion, id')
        ->get()
        ->toArray();

        return response()->json($data);
    }

    public function getStringId($obj): string {
        if (is_string($obj) && Str::contains($obj, '|')) {
            return trim(Str::before($obj, '|'));
        }
        return (string) $obj;
    }

    public function destroy(History $history): JsonResponse {
        $history->delete();
        return response()->json([
            'status'    => (bool) $history,
            'type'      => $history ? 'success' : 'error',
            'message'   => $history ? 'Historia clínica eliminada correctamente' : 'Error al eliminar la historia clínica',
        ], $history ? 200 : 400);
    }
}
