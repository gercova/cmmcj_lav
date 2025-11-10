<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRequest;
use App\Http\Requests\SubmoduleRequest;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\SubmoduleResource;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Submodule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ModulesController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:modulo_acceder')->only('index');
		$this->middleware('permission:modulo_ver')->only('list', 'show');
        $this->middleware('permission:modulo_guardar')->only('store');
		$this->middleware('permission:modulo_borrar')->only('destroy');
    }

    public function index(): View {
        $modules = Module::get();
        $submodules = Submodule::get();
        return view('security.modules.index', compact('modules', 'submodules'));
    }

    public function list(): JsonResponse {
        $resultsM   = Module::with('submodules')->where('id', '<>', 1)->get();
        $data       = $resultsM->map(function($item, $key) {
            $submodulesList = '<table class="table table-sm">';
            foreach ($item->submodules as $sm) {
                $submodulesList .= sprintf(
                    '<tr>
                        <td><span class="badge badge-success"><i class="%s"></i></span>&nbsp;%s</td>
                        <td>
                            <button class="btn btn-sm btn-warning update-row-submodule btn-sm" value="%s"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-danger delete-submodule btn-sm" value="%s"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>',
                    $sm->icono,
                    $sm->descripcion,
                    $sm->id,
                    $sm->id,
                );
            }
            
            $submodulesList .= '</table>';
            return [
                $key + 1,
                $item->descripcion,
                $submodulesList,
                $item->created_at->format('Y-m-d H:i:s'),
                sprintf(
                    '<button type="button" class="btn btn-sm btn-warning update-row-module btn-md" value="%s">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete-module btn-md" value="%s">
                        <i class="bi bi-trash"></i>
                    </button>',
                    $item->id,
                    $item->id,
                    $item->id
                ),
            ];
        });

        return response()->json([
            "sEcho"					    => 1,
            "iTotalRecords"			    => $data->count(),
            "iTotalDisplayRecords"	    => $data->count(),
            "aaData"				    => $data,
        ]);
    }

    protected function buildActionDropdown($id, $canEdit, $canDelete): string {
        if (!$canEdit && !$canDelete) return '<span class="text-muted">Sin acciones</span>';
        
        $buttons = [];
        if ($canEdit) {
            $buttons[] = '<li><a class="dropdown-item update-row" type="button" value="'.e($id).'"><i class="bi bi-pencil-square"></i> Editar</a></li>';
        }
        if ($canDelete) {
            $buttons[] = '<li><a class="dropdown-item delete-drug" type="button" value="'.e($id).'"><i class="bi bi-trash"></i> Eliminar</a></li>';
        }
        
        return '<div class="btn-group">
            <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">Acciones</button>
            <ul class="dropdown-menu">'.implode('', $buttons).'</ul>
        </div>';
    }

    public function store(ModuleRequest $request): JsonResponse {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $result = Module::updateOrCreate(['id' => $request->input('id')], $validated);
            if($result->wasRecentlyCreated){
                Permission::create([
                    'submodule_id'  => null,
                    'name'          => $validated['descripcion'],
                    'guard_name'    => 'web',
                    'descripcion'   => "El permiso sive para dar acceso a los módulos principales",
                ]);
            }
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Módulo actualizado correctamente' : 'Módulo creado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => $th->getMessage(),
            ], 400);
        }
    }

    public function storeSub(SubmoduleRequest $request): JsonResponse {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $result = Submodule::updateOrCreate(['id' => $request->input('id')], $validated);
            // Permisos
            $crudPermissions = ['acceder', 'ver', 'crear', 'editar', 'guardar', 'borrar'];
            if ($result->wasRecentlyCreated) {
                foreach ($crudPermissions as $action) {
                    Permission::create([
                        'name'          => "{$result->nombre}_{$action}", 
                        'descripcion'   => "El permiso sirve para asignar la función de {$action} en el submódulo de {$result->nombre}",
                        'submodule_id'  => $result->id,
                    ]); 
                }
            }
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Submódulo actualizado correctamente' : 'Submódulo creado correctamente',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => $th->getMessage(),
            ], 400);
        }
    }

    public function show(Module $module): JsonResponse {
        return response()->json(ModuleResource::make($module), 200);
    }

    public function showSub(Submodule $submodule): JsonResponse {
        $submodule->load('module');
        return response()->json(SubmoduleResource::make($submodule), 200);
    }

    public function delete(Module $module): JsonResponse {
        $module->delete();
        return response()->json([
            'status'    => true,
            'type'      => 'success',
            'message'   => 'Módulo eliminado correctamente',
        ], 200);
    }
}
