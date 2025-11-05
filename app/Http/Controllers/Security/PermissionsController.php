<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionValidate;
use App\Http\Resources\PermissionResource;
use App\Models\Module;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Submodule;

class PermissionsController extends Controller {

    public function __contruct(){
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:especialidad_acceder')->only('index');
		$this->middleware('permission:especialidad_ver')->only('list', 'show');
        $this->middleware('permission:especialidad_guardar')->only('store');
		$this->middleware('permission:especialidad_borrar')->only('destroy');
    }

    public function index(): View {
        $md = Module::get();
        $sub = Submodule::get();
        return view('security.permissions.index', compact('md', 'sub'));
    }

    public function list(): JsonResponse {
        $results = Permission::with('submodule')->orderBy('permissions.name', 'asc')->get();
        $data = $results->map(function($item, $index){
            $submodulesList = '<table class="table table-sm">';
            if ($item->submodule) {
                $submodulesList .= sprintf(
                    '<tr>
                        <td><span class="badge badge-success">%s</span></td>
                    </tr>',
                    ucfirst($item->submodule->nombre),
                );
            } else {
                $submodulesList .= '<tr><td><span class="badge badge-warning">Sin submódulo</span></td></tr>';
            }
            
            $submodulesList .= '</table>';
            
            return [
                $index + 1,
                ucfirst($item->name),
                $item->guard_name,
                $submodulesList,
                $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
                sprintf(
                    '<div class="btn-group">
                        <button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item update-row" type="button" value="%s"> <i class="bi bi-pencil-square"></i> Editar</a></li>
                            <li><a class="dropdown-item delete-permission" type="button" value="%s"> <i class="bi bi-trash"></i> Eliminar</a></li>       
                        </ul>
                    </div>',
                    $item->id,
                    $item->id
                ),
            ];
        });

        return response()->json([
            "sEcho"                 => 1,
            "iTotalRecords"         => $data->count(),
            "iTotalDisplayRecords"  => $data->count(),
            "aaData"                => $data,
        ]);
    }

    public function store(PermissionValidate $request): JsonResponse {
        $validated = $request->validated();

        // Permisos
        $crudPermissions    = ['acceder', 'ver', 'crear', 'editar', 'guardar', 'borrar'];
        $crudDescriptions   = [

        ];
        

        /*foreach($modulos as $m) {
            Permission::create(['name' => $m]);
        }*/
        

        // foreach ($modules as $module) {
            foreach ($crudPermissions as $action) {

                Permission::create(['name' => "{$module}_{$action}", 'descripcion' => ""]);
            }
        // }

        DB::beginTransaction();
        try {
            $result = Permission::updateOrCreate(['id' => $request->input('id')], $validated);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Permiso actualizado correctamente' : 'Permiso creado correctamente',
            ], 200);
        } catch(\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => true,
                'type'      => 'error',
                'message'   => 'Hubo un error al eliminar el permiso',
                'error'     => $th->getMessage(),
            ], 500);
        }
    }

    public function show(Permission $permission): JsonResponse {
        $permission->load(['submodule.module']);
        return response()->json(PermissionResource::make($permission), 200);
    }

    public function destroy(Permission $permission): JsonResponse {
        $permission->delete();
        return response()->json([
            'status'    => (bool) $permission,
            'type'      => $permission ? 'success' : 'error',
            'message'   => $permission ? 
                'Permiso eliminado correctamente' : 
                'Hubo un error al intentar eliminar', 
        ], $permission ? 200 : 400);
    }
}
