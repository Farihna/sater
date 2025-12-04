<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Partner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function adminLogin()
    {
        return view('auth.login');
    }
    public function userLogin()
    {
        return view('landing.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string', // Mengganti 'username' dengan 'login_id'
            'password' => 'required|string',
        ]);

        $loginId = $request->input('login_id');

        $field = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $loginId,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            $admin = Auth::user()->role === 'admin';

            if ($admin) {
                return redirect()->intended(route('admin.dashboard'));
            } 
            return redirect()->intended(route('index'));
        }
        return back()->withErrors([
            'login_id' => 'Email/Username atau password salah.',
        ])->withInput($request->only('login_id'));
    }

    public function logout(Request $request)
    {
        $role = Auth::user()->role;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'admin') {
            return redirect()->route('admin.login');
        }
        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('index'));
    }

    public function partnerRegister(Request $request)
    {
        $validate = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'nik' => 'required|string|max:20|unique:partners,nik',
            'identity_document' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $user = User::create([
            'username' => $validate['username'],
            'email' => $validate['email'] ?? null,
            'phone' => $validate['phone'],
            'password' => Hash::make($validate['password']),
            'role' => 'mitra',
        ]);

        $identityPath = null;
        if ($request->hasFile('identity_document')) {
            $identityPath = $request->file('identity_document')->store('partner_identities', 'local');
        }

        Partner::create([
            'user_id' => $user->id,
            'company_name' => $validate['company_name'],
            'address' => $validate['address'],
            'nik' => $validate['nik'],
            'identity_document' => $identityPath,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('index'));
    }

    public function servePartnerIdentity(Partner $partner)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'admin' && $user->id !== $partner->user_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat dokumen ini.');
        }

        if (empty($partner->identity_document) || !Storage::disk('local')->exists($partner->identity_document)) {
            abort(404);
        }

        $path = Storage::disk('local')->path($partner->identity_document);
        return response()->file($path);
    }
}