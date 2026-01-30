<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = AdminUser::where('username', $validated['username'])->first();

        if (!$admin || !Hash::check($validated['password'], $admin->password)) {
            return back()->withErrors(['username' => 'Invalid credentials']);
        }

        if (!$admin->is_active) {
            return back()->withErrors(['username' => 'Account is disabled']);
        }

        // Update last login
        $admin->update(['last_login_at' => now()]);

        session(['admin_id' => $admin->id, 'admin' => $admin]);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Handle admin logout
     */
    public function logout()
    {
        session()->forget(['admin_id', 'admin']);
        return redirect()->route('admin.login');
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('admin.auth.change-password');
    }

    /**
     * Handle password change
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = AdminUser::find(session('admin_id'));

        if (!Hash::check($validated['current_password'], $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $admin->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password changed successfully');
    }
}
