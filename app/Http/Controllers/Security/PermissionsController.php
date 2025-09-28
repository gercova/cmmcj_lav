<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionValidate;
use App\Http\Resources\PermissionResource;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller {

    public function __contruct(){
        $this->middleware(['auth', 'prevent.back']);
    }

    public function index(): View {
        return view('security.permissions.index');
    }

    public function list(): JsonResponse {
        $results    = Permission::orderBy('name', 'asc')->get();
        $data       = $results->map(function($item, $index){
            return [
                $index + 1,
                $item->name,
                $item->guard_name,
                $item->created_at->format('Y-m-d H:i:s'),
                sprintf(
                    '<button type="button" class="btn btn-sm btn-warning update-row btn-md" value="%s">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete-permission btn-md" value="%s">
                        <i class="bi bi-trash"></i>
                    </button>',
                    $item->id,
                    $item->id
                )
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
        ], $permission ? 200 : 500);
    }
}
