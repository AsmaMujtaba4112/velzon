<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Show Signup Form
    public function showSignupForm()
    {
        return view('auth.signup');
    }

    // Signup Logic
    public function signup(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'number'     => 'required|string|max:20',
            'password'   => 'required|string|min:8|confirmed'
        ]);

        $token = Str::random(60);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email' => $request->email,
            'number' => $request->number,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'email_verification_token' => $token
        ]);

        // Send verification email (no blade, simple HTML)
        Mail::to($user->email)->send(new VerifyEmailMail(route('verify.email', $token)));

        return redirect('login')->with('success', 'Signup successful! Please check your email to verify your account.');
    }

    // Verify Email
    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect('login')->withErrors(['email' => 'Invalid or expired verification link.']);
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return redirect('login')->with('success', 'Email verified successfully. You can now log in.');
    }

    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login Logic
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();

            if (!$user->email_verified_at) {
                Auth::logout();
                return back()->withErrors(['email' => 'Please verify your email before logging in.']);
            }

            return redirect('profile');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }

    // Show Profile
    public function profile()
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'number'     => 'nullable|string|max:20',
            'password'   => 'nullable|min:6|confirmed'
        ]);

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->number     = $request->number;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // change password
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Old password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }

    // Show Forgot Password Form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Send Reset Password Link
    public function sendResetToken(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        $token = Str::random(60);
        $user->reset_password_token = $token;
        $user->reset_token_expires_at = Carbon::now()->addHour();
        $user->save();

        // Send reset password email (no blade, simple HTML)
        Mail::to($user->email)->send(new ResetPasswordMail(url('reset-password/' . $token)));

        return back()->with('success', 'Password reset link sent to your email.');
    }

    // Show Reset Password Form
    public function showResetForm($token)
    {
        return view('auth.reset', compact('token'));
    }

    // Reset Password Logic
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('reset_password_token', $request->token)
                    ->where('reset_token_expires_at', '>', now())
                    ->firstOrFail();

        $user->password = Hash::make($request->password);
        $user->reset_password_token = null;
        $user->reset_token_expires_at = null;
        $user->save();

        return redirect('login')->with('success', 'Password reset successfully.');
    }

}
