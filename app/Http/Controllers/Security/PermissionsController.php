<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
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
        
    }

    public function store(Request $request): JsonResponse {
        
        DB::beginTransaction();
        try {

            DB::commit();
            return response()->json([

            ], 200);

        } catch(\Throwable $th) {
            DB::rollBack();
            return response()->json([

            ], 500);
        }
    }

    public function show(Permission $permission): JsonResponse {
        return response()->json();
    }

    public function destroy(Permission $permission): JsonResponse {
        $permission->delete();
        return response()->json([
            'status'    => (bool) $permission,
            'type'      => $permission ? 'success' : 'error',
            'message'   => $permission ? 'Permiso eliminado correctamente' : 'Hubo un error al intentar eliminar', 
        ], $permission ? 200 : 500);
    }
}
