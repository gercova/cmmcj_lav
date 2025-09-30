<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EnterpriseController extends Controller {
    
    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:empresa_acceder')->only('index');
		$this->middleware('permission:empresa_ver')->only('getEnterprise', 'getImages');
        $this->middleware('permission:empresa_guardar')->only('store');
    }

    public function index(): View {
        $etp = Enterprise::first();
        return view('enterprise.index', compact('etp'));
    }

    public function store(Request $request): JsonResponse {
        $op = $request->input('op');
        $enterprise = Enterprise::where('id', $request->input('id'))->first();
        if (!$enterprise) return response()->json([
            'status'    => false, 
            'type'      => 'error', 
            'message'  => 'Empresa no encontrada'
        ], 404);
        
        $response = ['status' => false, 'type' => 'error', 'message' => 'No se pudo actualizar'];
        DB::beginTransaction();
        try {
            switch ($op) {
                case 1:
                    $data = $request->only(['ruc', 'razon_social', 'nombre_comercial', 'rubro_empresa', 'codigo_pais', 'telefono_comercial', 'email_comercial', 'pais', 'ciudad', 'direccion', 'pagina_web', 'representante_legal', 'frase_empresa', 'fecha_creacion']);
                    $data['nombre_comercial']       = trim(request('nombre_comercial') ?? '');
                    $data['razon_social']           = trim(request('razon_social') ?? '');
                    $data['representante_legal']    = trim(request('representante_legal') ?? '');
    
                    if ($enterprise->update($data)) $response = ['status' => true, 'type' => 'success', 'message' => 'Actualizado correctamente'];
                    break;

                case 2:
                    if ($request->hasFile('foto-representante')) {
                        $this->uploadFile($enterprise, 'foto_representante', $request->file('foto-representante'));
                        $response = ['status' => true, 'type' => 'success', 'message' => 'Foto actualizada correctamente'];
                    }
                    break;
    
                case 3:
                    if ($request->hasFile('logo')) {
                        $this->uploadFile($enterprise, 'logo_principal', $request->file('logo'));
                        $response = ['status' => true, 'type' => 'success', 'message' => 'Logo actualizado correctamente'];
                    }
                    break;
    
                case 4:
                    if ($request->hasFile('mini-logo')) {
                        $this->uploadFile($enterprise, 'logo_miniatura', $request->file('mini-logo'));
                        $response = ['status' => true, 'type' => 'success', 'message' => 'Mini-logo actualizado correctamente'];
                    }
                    break;
    
                default:
                    $response = ['status' => false, 'type' => 'error', 'message' => 'Operación no válida'];
            }

            DB::commit();

        } catch(\Throwable $th) {
            DB::rollBack();
            $response = [
                'status'    => false,
                'type'      => 'error',
                'message'   => 'Error al guardar la información',
                'error'     => $th->getMessage(),
            ];
        }
        
        return response()->json($response, 200);
    }

    private function uploadFile($model, $field, $file) {
        $previousPath   = $model->getRawOriginal($field);
        $filename       = Str::random(10).'.'.$file->extension();
        $path           = $file->storeAs('/photos', $filename, 'public');
        $model->update([$field => $path]);
        
        if ($previousPath) Storage::delete($previousPath);
        return $path;
    }

    public function getEnterprise(): JsonResponse {
        $info = Enterprise::where('id', 1)->get();
        return response()->json(['info' => $info], 200);
    }

    public function getImages(): JsonResponse {
        $enterprise = Enterprise::find(1);
        if (!$enterprise) return response()->json(['status' => false, 'message' => 'Empresa no encontrada'], 404);
        // Devuelve las rutas de las imágenes
        return response()->json([
            'status'                => true,
            'foto_representante'    => asset('storage/'.$enterprise->foto_representante),
            'logo'                  => asset('storage/'.$enterprise->logo_principal),
            'logo_mini'             => asset('storage/'.$enterprise->logo_miniatura),
        ], 200);
    }
}
