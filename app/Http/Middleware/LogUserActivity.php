<?php

namespace App\Http\Middleware;

use App\Jobs\LogActivityJob;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\ActivityLog;

class LogUserActivity {

    public function handle(Request $request, Closure $next) {
        $response = $next($request);

        if (Auth::check() && $this->shouldLogRoute($request)) {
            $currentRoute = Route::currentRouteName();
            $module = $this->getModuleName($currentRoute);

            // Despachar Job para registro asíncrono
            LogActivityJob::dispatch([
                'action'        => 'access',
                'module'        => $module,
                'user_id'       => Auth::id(),
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        return $response;
    }

    private function getModuleName($routeName): string {
        $modules = [
            'users' => 'Usuarios',
            'products' => 'Productos',
            'clients' => 'Clientes',
            'dashboard' => 'Dashboard',
            'profile' => 'Perfil',
            'settings' => 'Configuración',
            // Agrega más módulos según tu aplicación
        ];

        foreach ($modules as $key => $name) {
            if (str_contains($routeName, $key)) {
                return $name;
            }
        }

        return 'Sistema';
    }

    private function shouldLogRoute(Request $request): bool {
        // Excluir rutas que no quieres loguear
        $excludedRoutes = [
            'login',
            'logout',
            'password.reset',
            'verification.send',
            'sanctum.csrf-cookie'
        ];

        $currentRoute = Route::currentRouteName();

        foreach ($excludedRoutes as $route) {
            if (str_contains($currentRoute, $route)) {
                return false;
            }
        }

        return $request->isMethod('GET') && !$request->ajax();
    }
}
