<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function performAuth(Request $request)
    {
        $settings = Setting::pluck('value', 'key');
        if ($request->auth_type === 'sign_up') {
            $is_email_verification_enabled = $settings->get('is_email_verification_enabled') && (int) $settings->get('is_email_verification_enabled') === 1;
            $request->validate([
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:8',
                'auth_type' => 'required|in:sign_up,login',
                'full_name' => 'sometimes|required|string|max:255',
            ]);

            if ($is_email_verification_enabled) {
                $emailVerificationToken = bin2hex(random_bytes(32));
            } else {
                $emailVerificationToken = null;
            }

            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'signup_method' => 'email',
                'email_verification_token' => $emailVerificationToken,
                'email_verified' => $is_email_verification_enabled ? 0 : 1,
            ]);

            if ($is_email_verification_enabled) {
                $this->sendVerificationEmail($user);
            }

            if ($is_email_verification_enabled) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Signup Successful! Please verify your email address.',
                    'redirect_url' => route('notify', ['email' => $user->email, 'type' => 'email-verification']),
                ], 201);
            } else {
                Auth::login($user);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Logged In!',
                    'redirect_url' => redirect()->intended(url()->previous())->getTargetUrl() ?? url()->previous(),
                ]);
            }
        }

        if ($request->auth_type === 'login') {
            $request->validate([
                'email' => 'required|email|max:255',
                'password' => 'required|min:8',
                'auth_type' => 'required|in:sign_up,login',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found. Please sign up.',
                ], 404);
            }

            if ($user->signup_method !== 'email') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please log in using '.ucfirst($user->signup_method).'.',
                ], 403);
            }

            if (! $user->email_verified) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please verify your email address before logging in.',
                ], 403);
            }

            $remember = $request->has('remember');

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Logged In!',
                    'redirect_url' => redirect()->intended(url()->previous())->getTargetUrl() ?? url()->previous(),
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password.',
            ], 401);
        }
    }

    public function sendVerificationEmail($user)
    {
        try {
            $settings = Setting::pluck('value', 'key');
            $headerLogo = $settings->get('header_logo') ?? 'admin/assets/images/placeholder-logo.png';

            $data = [
                'settings' => $settings,
                'full_name' => $user->full_name,
                'verify_link' => route('auth.verify-email', ['token' => $user->email_verification_token]),
                'logo' => asset($headerLogo),
            ];

            $finalSubject = 'Please Verify Your Email Address - '.env('MAIL_FROM_NAME');
            Mail::send('emails.verify-email', ['data' => $data], function ($message) use ($user, $finalSubject) {
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message
                    ->to($user->email)
                    ->subject($finalSubject);
            });

            return true;
        } catch (\Throwable $e) {
            \Log::error('Verification email failed for user '.$user->id.': '.$e->getMessage());

            return false;
        }
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (! $user) {
            return redirect()
                ->route('frontend.index')
                ->with('notify_error', 'The verification link is invalid or expired.');
        }

        $user->email_verified = true;
        $user->email_verification_token = null;
        $user->save();

        Auth::login($user);

        return redirect()
            ->route('frontend.index')
            ->with('notify_success', 'Your email has been verified successfully! You are now logged in.');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            if ($user->signup_method != 'email') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please log in using '.ucfirst($user->signup_method).'.',
                ], 401);
            }

            if (! $user || $user->email_verified == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please verify your email before logging in.',
                ], 401);
            }

            return response()->json(['status' => 'success', 'user' => $user, 'challenge' => 'login']);
        }

        return response()->json(['status' => 'error', 'message' => 'Email not found.', 'challenge' => 'sign_up']);
    }

    public function notify(Request $request)
    {
        $email = $request->input('email');
        $type = $request->input('type');

        // Basic validation for input
        if (! $email || ! $type) {
            return redirect()->back()->with('notify_error', 'Invalid request parameters.');
        }

        if ($type == 'email-verification') {
            $user = User::where('email', $email)->first();
            if (! $user) {
                return redirect()->route('frontend.index')->with('notify_error', 'Email not found.');
            }
            if ($user->email_verified) {
                return redirect()->route('frontend.index')->with('notify_error', 'Email already verified.');
            }

            $title = 'Please Verify Your Email!';
            $desc = "We've sent a verification link to <a style='color: var(--color-primary);' href='javascript:void(0)' > <strong> $email </strong> </a>. Please check your inbox and click on the link to confirm your email address.";
        } elseif ($type == 'reset-password') {
            $passwordReset = DB::table('password_resets')->where('email', $email)->first();
            if (! $passwordReset) {
                return redirect()->route('frontend.index')->with('notify_error', 'Password reset request not found.');
            }

            $title = 'Reset Password';
            $desc = "We've sent a Reset Password link to <a href='javascript:void(0)' style='color: var(--color-primary);' > <strong> $email </strong> </a>. Please check your inbox and click on the link to reset your password.";
        } else {
            return redirect()->back()->with('notify_error', 'Page not available');
        }

        return view('notify')
            ->with('title', $title)
            ->with('desc', $desc);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->back()->with('notify_success', 'Logged Out!');
    }
}
