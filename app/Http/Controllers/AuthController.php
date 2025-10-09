<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // Redirect to appropriate page if already authenticated
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && $user->is_public_user) {
                return redirect()->route('form.index');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Show the public user login/register form.
     */
    public function showPublicLoginForm()
    {
        // Redirect to appropriate page if already authenticated
        if (Auth::check()) {
            if (Auth::user()->is_public_user) {
                return redirect()->route('form.index');
            }
            return redirect()->route('dashboard');
        }

        return view('auth.loginuser');
    }

    /**
     * Handle login authentication.
     */
    public function login(Request $request)
    {
        // Validate the login form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Rate limiting to prevent brute force attacks
        $key = 'login_attempts_' . $request->ip();
        $maxAttempts = 5;
        $decayMinutes = 15;

        if (cache()->has($key) && cache()->get($key) >= $maxAttempts) {
            throw ValidationException::withMessages([
                'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $decayMinutes . ' menit.',
            ]);
        }

        // Attempt to authenticate
        $remember = $request->boolean('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            // Clear login attempts on successful login
            cache()->forget($key);
            
            $request->session()->regenerate();

            // Check if user is a public user (should not be able to login via admin login)
            if (Auth::user()->is_public_user) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini adalah akun publik. Silakan masuk melalui /loginuser',
                ])->withInput();
            }

            // Redirect to intended URL or dashboard (admin/staff only)
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang! Anda berhasil masuk.');
        }

        // Increment login attempts
        $attempts = cache()->get($key, 0) + 1;
        cache()->put($key, $attempts, now()->addMinutes($decayMinutes));

        // Authentication failed
        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        // Check user type BEFORE logging out
        $isPublicUser = Auth::check() && Auth::user()->is_public_user;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to appropriate login page based on user type
        if ($isPublicUser) {
            return redirect()->route('loginuser')->with('success', 'Anda telah berhasil keluar.');
        } else {
            return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
        }
    }

    /**
     * Handle public user login (using name and phone number).
     */
    public function publicLogin(Request $request)
    {
        // Validate the login form
        $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'], // This is phone_number
        ], [
            'name.required' => 'Nama Lengkap wajib diisi.',
            'password.required' => 'Nomor HP wajib diisi.',
        ]);

        // Rate limiting to prevent brute force attacks
        $key = 'public_login_attempts_' . $request->ip();
        $maxAttempts = 5;
        $decayMinutes = 15;

        if (cache()->has($key) && cache()->get($key) >= $maxAttempts) {
            return back()->withErrors([
                'name' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $decayMinutes . ' menit.',
            ])->withInput();
        }

        // Find user by name and phone_number
        $user = User::where('name', $request->name)
                    ->where('phone_number', $request->password)
                    ->where('is_public_user', true)
                    ->first();

        if (!$user) {
            // Increment login attempts
            $attempts = cache()->get($key, 0) + 1;
            cache()->put($key, $attempts, now()->addMinutes($decayMinutes));

            return back()->withErrors([
                'name' => 'Nama Lengkap atau Nomor HP salah.',
            ])->withInput();
        }

        // Clear login attempts on successful login
        cache()->forget($key);

        // Clear any existing sessions
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Log the user in
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Redirect to form page
        return redirect()->route('form.index')->with('success', "Selamat datang, {$user->name}!");
    }

    /**
     * Handle public user registration.
     */
    public function publicRegister(Request $request)
    {
        // Validate the registration form
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:8', 'max:20', 'unique:users,phone_number'],
        ], [
            'name.required' => 'Nama Lengkap wajib diisi.',
            'name.max' => 'Nama Lengkap maksimal 255 karakter.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.min' => 'Nomor HP minimal 8 digit.',
            'phone.max' => 'Nomor HP maksimal 20 digit.',
            'phone.unique' => 'Nomor HP ini sudah terdaftar. Silakan masuk atau gunakan nomor lain.',
        ]);

        // Create new public user
        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone,
            'password' => Hash::make($request->phone), // Hash the phone number as password
            'is_public_user' => true,
        ]);

        // Log the user in automatically after registration
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect to form page
        return redirect()->route('form.index')->with('success', "Registrasi berhasil! Selamat datang, {$user->name}.");
    }

    /**
     * Create default admin user if it doesn't exist.
     * This method can be called from a seeder or artisan command.
     */
    public function createDefaultAdmin()
    {
        $adminEmail = 'admin@igd.com';
        
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]);
            
            return "Default admin user created successfully. Email: {$adminEmail}, Password: admin123";
        }
        
        return "Admin user already exists.";
    }
}