<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionValidate;
use App\Http\Resources\PermissionResource;
use App\Models\Module;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
//use App\Models\Permission;

class PermissionsController extends Controller {

    public function __contruct(){
        $this->middleware(['auth', 'prevent.back']);
        $this->middleware('permission:especialidad_acceder')->only('index');
		$this->middleware('permission:especialidad_ver')->only('list', 'show');
        $this->middleware('permission:especialidad_guardar')->only('store');
		$this->middleware('permission:especialidad_borrar')->only('destroy');
    }

    public function index(): View {
        $md = Module::all();
        return view('security.permissions.index', compact('md'));
    }

    public function list(): JsonResponse {
        $results    = Permission::selectRaw('permissions.name as permission, permissions.guard_name, m.descripcion as module, permissions.created_at, permissions.id')
            ->join('modules as m', 'permissions.module_id', '=', 'm.id')->orderBy('name', 'asc')->get();
        $data       = $results->map(function($item, $index){
            return [
                $index + 1,
                $item->permission,
                $item->guard_name,
                sprintf(
                    '<span class="badge badge-success">%s</span>',
                    $item->module
                ),
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
            "sEcho"					    => 1,
            "iTotalRecords"			    => $data->count(),
            "iTotalDisplayRecords"	    => $data->count(),
            "aaData"				    => $data,
        ]);
    }

    public function store(PermissionValidate $request): JsonResponse {
        $validated = $request->validated();

        //dd($validated);

        DB::beginTransaction();
        try {
            $result = Permission::updateOrCreate(['id' => $request->input('id')], $validated);
            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Permiso actualizado correctamente' : 'Permiso guardado correctamente',
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
        return response()->json(PermissionResource::make($permission));
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
