<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
  public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $user = $request->user();

    // Cek status aktif akun (Bab 9: Keamanan & Privasi)
    if (isset($user->is_active) && ! $user->is_active) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi Administrator sekolah.');
    }

    $request->session()->regenerate();

    $role = $user->role instanceof \BackedEnum ? $user->role->value : $user->role;

    return match ($role) {
        'admin'   => redirect()->intended(route('admin.dashboard')),
        'petugas' => redirect()->intended(route('petugas.dashboard')),
        default   => redirect()->intended(route('guru.dashboard')),
    };
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
