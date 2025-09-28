<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserValidate;
use App\Models\Enterprise;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'prevent.back']);
    }
    
    public function index(): View {
        return view('security.user.index');
    }

    public function new(): View {
        $rl = Role::all();
        $sp = Specialty::all();
        return view('security.user.new', compact('rl', 'sp'));
    }

    public function edit(User $user): View {
        $rl = Role::all();
        $ur = DB::table('model_has_roles')->where('model_id', $user->id)->get();
        $sp = Specialty::all();
        return view('security.user.edit', compact('user', 'rl', 'ur', 'sp'));   
    }

    public function role(User $user): View {
        $user                   = User::with(['permissions', 'roles.permissions'])->findOrFail($user->id);
        // Obtener todos los permisos del sistema
        $allPermissions         = Permission::all();
        // Permisos directos del usuario
        $directPermissions      = $user->permissions;
        // Permisos heredados de roles
        $rolePermissions        = $user->getPermissionsViaRoles();
        // Combinar todos los permisos asignados (directos y por roles)
        $allAssignedPermissions = $directPermissions->merge($rolePermissions)->unique('id');
        // Permisos disponibles (no asignados ni directos ni por roles)
        $availablePermissions   = $allPermissions->diff($allAssignedPermissions);
        return view('security.user.role', compact('user', 'availablePermissions', 'directPermissions', 'rolePermissions', 'allAssignedPermissions'));
    }

    public function list(): JsonResponse {
        $results    = DB::table('view_user_roles_last_login')->get();
        $data       = $results->map(function ($item, $index) {
            $currentUser = auth()->user();
            $buttons = '';
            if ($currentUser->hasRole('Administrador')) {
                $buttons .= sprintf(
                    '<a href="%s" class="btn btn-sm btn-info assign-roles btn-md" title="Asignar roles">
                        <i class="bi bi-person-gear"></i>
                    </a>&nbsp;',
                    htmlspecialchars(route('security.users.role', ['user' => $item->id]), ENT_QUOTES, 'UTF-8')
                );
            }
            if ($currentUser->can('usuario_editar')) {
                $buttons .= sprintf(
                    '<a href="%s" class="btn btn-sm btn-warning update-row btn-md">
                        <i class="bi bi-pencil-square"></i>
                    </a>&nbsp;',
                    htmlspecialchars(route('security.users.edit', ['user' => $item->id]), ENT_QUOTES, 'UTF-8')
                );
            }
            if ($currentUser->can('usuario_borrar')) {
                $buttons .= sprintf(
                    '<button type="button" class="btn btn-sm btn-danger delete-user btn-md" value="%s" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>',
                    htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8')
                );
            }
            return [
                $index + 1,
                $item->name,
                $item->email,
                sprintf(
                    '<span class="badge badge-success">%s</span>',
                    $item->rol
                ),
                $item->last_login ?: 'Nunca',
                $buttons ?: '<span class="text-muted">No autorizado</span>'
            ];
        });

        return response()->json([
            'sEcho'                 => 1,
            'iTotalRecords'         => $data->count(),
            'iTotalDisplayRecords'  => $data->count(),
            'aaData'                => $data,
        ]);
    } 

    public function store(UserValidate $request): JsonResponse {
        $validated  = $request->validated();
        $id         = $request->input('id');
        // Inicializamos $path como null para evitar "Undefined variable"
        $path       = null;
        // Procesar la imagen si existe
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            // Eliminar imagen anterior si estamos actualizando
            if ($id) {
                $user = User::find($id);
                if ($user && $user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            // Limpiar nombre del archivo
            $originalName   = $image->getClientOriginalName();
            $cleanName      = $this->cleanFileName($originalName);
            // Subir la imagen al disco público, dentro de la carpeta 'users'
            $path           = $image->storeAs('users', $cleanName, 'public');
        }
        // Preparar campos base
        $fields = [
            'name'              => $validated['name'],
            'especialidad_id'   => $validated['especialidad_id'],
        ];
        // Preparar campos procesados
        $nickname   = $validated['role_id'] == 1 ? $request->input('username') : $this->createNickname($validated['name']);
        $domain     = Enterprise::findOrFail(1)->pagina_web;
        $proccessFields = [
            'username'  => $nickname,
            'email'     => $nickname.'@'.$domain,
            'password'  => $request->filled('password') ? Hash::make($request->password) : Hash::make('password'),
            'avatar'    => $path ?? ($id ? User::find($id)->avatar : null),
        ];

        $data = array_merge($fields, $proccessFields);
        DB::beginTransaction();
        try {
            $result = User::updateOrCreate(['id' => $id], $data);
            // Actualizar rol si se proporciona
            if ($request->has('role_id')) {
                DB::table('model_has_roles')->where('model_id', $result->id)->where('model_type', 'App\Models\User')->delete();
                DB::table('model_has_roles')->insert([
                    'role_id'       => $request->input('role_id'),
                    'model_type'    => 'App\Models\User',
                    'model_id'      => $result->id,
                ]);
            }

            DB::commit();
            return response()->json([
                'status'    => true,
                'type'      => 'success',
                'message'   => $result->wasChanged() ? 'Datos del usuario actualizados correctamente' : 'Datos del usuario guardados correctamente',
                'redirect'  => route('security.users.home'),
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'type'      => 'error',
                'message'   => 'Ocurrió un problema al intentar guardar',
                'error'     => $th->getMessage(),
            ], 500);
        }
    }

    private function cleanFileName($filename) {
        // Remover caracteres especiales y espacios
        $clean = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename); 
        // Limitar longitud del nombre
        $clean = substr($clean, 0, 100);
        return $clean;
    }

    public function createNickname($name): string {
        $nickname   = '';
        $count      = mb_substr_count($name, ' ');
        $positionW  = explode(' ', $name);
    
        if ($count == 1) { 
            $w = substr($positionW[0], 0, -3); 
            $nickname = $w . $positionW[1];
        } elseif ($count == 2) {
            $nickname = $positionW[0][0] . $positionW[1] . $positionW[2][0];
        } elseif ($count == 3) {
            $nickname = $positionW[0][0] . $positionW[1] . $positionW[2] . $positionW[3][0];
        } elseif ($count == 4) {
            $w = substr($positionW[4], 0, -1);
            $nickname = $positionW[0][0] . $positionW[3] . $w;
        } else {
            // Caso por defecto: usar el primer nombre completo
            $nickname = strtolower(str_replace(' ', '', $name));
        }
        // Eliminar caracteres no alfanuméricos
        $nickname = preg_replace('/[^a-zA-Z0-9]/', '', $nickname);
        if(User::where('username', $nickname)->count() > 1) {
            $nickname .= rand(1, 99);
        }

        return strtolower($nickname);
    }

    public function destroy(User $user): JsonResponse {
        $user->delete();
        return response()->json([
            'status'    => (bool) $user,
            'type'      => $user ? 'success' : 'error',
            'message'   => $user ? 'Usuario eliminado correctamente' : 'Ocurrió un error al intenter eliminar el usuario',
        ], $user ? 200 : 500);
    }
}