<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\CartController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::guard('brand')->check()) {
            return redirect()->intended(route('brand.dashboard', absolute: false));
        }

        if (Auth::guard('buyer')->check()) {
            // Merge any guest session cart into the buyer's database cart
            CartController::mergeSessionCartToDb(Auth::guard('buyer')->id());
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if (Auth::guard('web')->check()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('brand')->check()) {
            Auth::guard('brand')->logout();
        } elseif (Auth::guard('buyer')->check()) {
            Auth::guard('buyer')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
