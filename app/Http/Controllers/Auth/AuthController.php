<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginValidate;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller {

    public function showLoginForm() {
        $enterprise = Enterprise::first();
        return view('auth.login', compact('enterprise'));
    }

    public function login(LoginValidate $request) {
        $validated = $request->validated();
        $this->checkTooManyFailedAttempts($request);

        $remember = $request->has('remember');

        if (Auth::attempt($validated, $remember)) {
            $user = Auth::user();

            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                return response()->json([
                    'status'    => false,
                    'message'   => 'Tu cuenta está desactivada.'
                ], 403);
            }

            // Autenticación exitosa
            $request->session()->regenerate();
            RateLimiter::clear($this->throttleKey($request));
            // Obtener permisos
            $permissions = Auth::user()->getAllPermissions()->pluck('name');
            return response()->json([
                'status'        => true,
                'message'       => 'Inicio de sesión exitoso.',
                'redirect'      => route('home'),
                'permissions'   => $permissions,
            ], 200);
        }

        RateLimiter::hit($this->throttleKey($request), 60);
        return response()->json([
            'status' => false,
            'message' => 'Credenciales inválidas'
        ], 401);
    }

    protected function throttleKey(Request $request): string {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    public function checkTooManyFailedAttempts(Request $request) {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        throw new \Exception('Demasiados intentos. Intente nuevamente en 1 minuto.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json([
            'status' => true,
            'redirect' => route('login')
        ], 200);
    }
}
