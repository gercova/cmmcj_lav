<?php
// app/Http/Middleware/AuditMiddleware.php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditMiddleware
{
    public function handle(Request $request, Closure $next) {
        $response = $next($request);

        // Registrar acciones específicas de ruta
        if (Auth::check() && $this->shouldLog($request)) {
            $this->logRouteAction($request);
        }

        return $response;
    }

    private function shouldLog(Request $request): bool {
        $routeName = $request->route()->getName();
        $methods = ['POST', 'PUT', 'PATCH', 'DELETE'];

        // Solo registrar métodos que modifican datos
        return in_array($request->method(), $methods) || in_array($routeName, ['login', 'logout', 'export', 'download']);
    }

    private function logRouteAction(Request $request) {
        $action = $this->getActionFromRoute($request);
        $description = $this->getRouteDescription($request);

        AuditLog::create([
            'action'        => $action,
            'description'   => $description,
            'url'           => $request->fullUrl(),
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent'),
            'user_id'       => Auth::id(),
        ]);
    }

    private function getActionFromRoute(Request $request): string {
        $routeName = $request->route()->getName();

        if (str_contains($routeName, 'login')) return 'login';
        if (str_contains($routeName, 'logout')) return 'logout';
        if (str_contains($routeName, 'export')) return 'exported';
        if (str_contains($routeName, 'download')) return 'downloaded';

        return strtolower($request->method());
    }

    private function getRouteDescription(Request $request): string {
        $routeName = $request->route()->getName();
        $method = $request->method();

        $descriptions = [
            'login' => 'Inicio de sesión',
            'logout' => 'Cierre de sesión',
            'POST' => 'Creación de registro',
            'PUT' => 'Actualización de registro',
            'PATCH' => 'Actualización parcial',
            'DELETE' => 'Eliminación de registro'
        ];

        return $descriptions[$routeName] ??
               $descriptions[$method] ??
               "Acción {$method} en {$routeName}";
    }
}
