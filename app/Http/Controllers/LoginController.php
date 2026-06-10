<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    // LOGIN PAGE
    public function login_page()
    {
        return view('auth.login');
    }

    // LOGIN CHECK
    public function login_check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (RateLimiter::tooManyAttempts($request->ip(), 5)) {
            return back()->withErrors([
                'email' => 'Too many attempts. Try again later.'
            ]);
        }
        RateLimiter::hit($request->ip(), 60);

        $user = DB::table('users_details')
            ->where('email', $request->email)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {

            Auth::loginUsingId($user->id);
            $request->session()->regenerate();

            session([
                'user_type' => $user->role
            ]);
            // 🌟 FIXED: Dynamic Routing Matrix based on assigned Roles
            if ($user->role === 'admin') {
                // Admins go to the central approval administration metrics screen
                return redirect('/dashboard');
            }

            // Regular users (waiting or active) head to the invoicing workplace
            // If they are not approved yet, our custom 'approved' middleware will securely catch them
            // and show the waiting notice screen automatically!
            // return redirect('/invoice/create');
            return redirect('/user/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid login credentials'
        ]);
    }

    // REGISTER PAGE
    public function register_page()
    {
        return view('auth.register');
    }

    // REGISTER STORE
    public function register_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users_details,email',
            'password' => 'required|min:6'
        ]);

        DB::table('users_details')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/login')->with('success', 'Registration successful. Please login.');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
