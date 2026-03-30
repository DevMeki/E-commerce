<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $accountType = $request->input('account_type', 'buyer');

        if ($accountType === 'brand') {
            $request->validate([
                'fullname' => 'required|string|max:100',
                'email' => 'required|string|lowercase|email|max:255|unique:'.\App\Models\Brand::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'brand_name' => 'required|string|max:100',
                'brand_slug' => 'required|string|max:100|unique:'.\App\Models\Brand::class,
                'brand_category' => 'required|string',
                'brand_location' => 'required|string|max:100',
            ]);

            $brand = \App\Models\Brand::create([
                'owner_name' => $request->fullname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'brand_name' => $request->brand_name,
                'slug' => $request->brand_slug,
                'category' => $request->brand_category,
                'location' => $request->brand_location,
                'status' => 'pending',
            ]);

            event(new Registered($brand));
            Auth::guard('brand')->login($brand);
            return to_route('brand.dashboard');
        }

        $request->validate([
            'fullname' => 'required|string|max:100',
            'email' => 'required|string|lowercase|email|max:255|unique:'.\App\Models\Buyer::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $buyer = \App\Models\Buyer::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($buyer));
        Auth::guard('buyer')->login($buyer);
        return to_route('home');
    }
}
