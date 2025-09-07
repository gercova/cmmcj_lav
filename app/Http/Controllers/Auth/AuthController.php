<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller {
    
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(LoginValidate $request) {
        $validated = $request->validated();
        $this->checkTooManyFailedAttempts();

        $remember = $request->has('remember');

        if (Auth::attempt($validated, $remember)) {
            $user = Auth::user();

            if (isset($user->is_active) && !$user->is_active) {
                Auth::logout();
                return response()->json([
                    'status' => false,
                    'message' => 'Tu cuenta está desactivada.'
                ], 403);
            }

            RateLimiter::clear($this->throttleKey());
            
            return response()->json([
                'success' => true,
                'redirect' => $user->isAdmin() ? '/admin/dashboard' : '/dashboard'
            ]);
        }

        RateLimiter::hit($this->throttleKey(), 60);

        return response()->json([
            'success' => false,
            'message' => 'Credenciales inválidas'
        ], 401);
    }

    public function throttleKey() {
        return Str::lower(request('email')) . '|' . request()->ip();
    }

    public function checkTooManyFailedAttempts() {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        throw new \Exception('Demasiados intentos. Intente nuevamente en 1 minuto.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
