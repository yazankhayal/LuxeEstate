<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

// Import your custom notification
// Import the Notification facade

class PasswordResetLinkController extends Controller
{
    /**
     * Show the forgot-password form.
     */
    public function create(Request $request)
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Send a password reset link to the given email.
     */
    public function store(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        // 1. Find the User
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [trans(Password::INVALID_USER)],
            ]);
        }

        // 2. Generate the safe, official secure token
        $token = Password::getRepository()->create($user);

        // 3. Trigger the custom notification explicitly right here
        Mail::to($user->email)->queue(new ResetPasswordMail($token, $user->email));

        // 4. Return back to your premium Inertia view
        return back()->with('status', trans(Password::RESET_LINK_SENT));
    }
}