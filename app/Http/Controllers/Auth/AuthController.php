<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendResetPasswordLink;
use App\Events\SendVerificationEmail;
use App\Http\Controllers\Controller;
use App\Models\ImageTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function perfrom_auth(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
            'auth_type' => 'required|in:sign_up,login',
            'full_name' => 'sometimes|required|string|max:255',
        ]);

        if ($request->auth_type == 'sign_up') {
            // Create a new user
            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'signup_method' => 'email',
                'email_verification_token' => Str::random(32),
            ]);

            // Send verification email
            event(new SendVerificationEmail($user));

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Signup Successful! Please verify your email address.',
                'redirect_url' => route('notify', ['email' => $user->email, 'type' => 'email-verification']),
            ]);
        } elseif ($request->auth_type == 'login') {
            $user = User::where('email', $request->email)->first();

            if ($user && $user->signup_method != 'email') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please log in using '.ucfirst($user->signup_method).'.',
                ]);
            }

            if ($user && ! $user->email_verified) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please verify your email address before logging in.',
                ]);
            }

            $remember = $request->has('remember'); // Check if 'remember' checkbox is selected

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Logged In!',
                    'redirect_url' => 'current',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid email or password.',
                    'old_input' => $request->only('email'),
                ]);
            }
        }
    }

    protected function sendVerificationEmail($user)
    {
        $data = [
            'full_name' => $user->full_name,
            'verify_link' => route('verify.email', ['token' => $user->email_verification_token]),
            'logo' => asset(ImageTable::where('table_name', 'logo')->latest()->first()->img_path ?? 'assets/images/logo (1).webp'),
        ];

        Mail::send('emails.verify-email', ['data' => $data], function ($message) use ($user) {
            $message->from(env('MAIL_FROM_ADDRESS'));
            $message->to($user->email);
            $message->subject('Please Verify Your Email Address - '.env('MAIL_FROM_NAME'));
        });
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (! $user) {
            return redirect()->route('index')->with('notify_error', 'The verification link is invalid or expired.');
        }

        $user->email_verified = true;
        $user->email_verification_token = null;
        $user->save();

        return redirect()->route('index')
            ->with('notify_success', 'Your email has been verified successfully! You can now login');
    }

    public function check_account(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {

            // Check if the user's signup method is not 'email'
            if ($user->signup_method != 'email') {

                // Inform the user to log in using their signup method
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please log in using '.ucfirst($user->signup_method).'.',

                ]);
            }

            // Check if the email is not verified
            if ($user->email_verified == 0) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'Please verify your email before logging in.',
                ]);
            }

            // Return user data if everything is fine
            return response()->json(['status' => '200', 'user' => $user]);
        }

        // Return 404 status if the user is not found
        return response()->json(['status' => '404']);
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
                return redirect()->route('index')->with('notify_error', 'Email not found.');
            }
            if ($user->email_verified) {
                return redirect()->route('index')->with('notify_error', 'Email already verified.');
            }

            $title = 'Please Verify Your Email!';
            $desc = "We've sent a verification link to <a href='javascript:void(0)' class='link-secondary link-offset-2 link-underline-opacity-50 link-underline-opacity-100-hover'> <strong> $email </strong> </a>. Please check your inbox and click on the link to confirm your email address.";

        } elseif ($type == 'reset-password') {
            $passwordReset = DB::table('password_resets')->where('email', $email)->first();
            if (! $passwordReset) {
                return redirect()->route('index')->with('notify_error', 'Password reset request not found.');
            }

            $title = 'Reset Password';
            $desc = "We've sent a Reset Password link to <a href='javascript:void(0)' class='link-secondary link-offset-2 link-underline-opacity-50 link-underline-opacity-100-hover'> <strong> $email </strong> </a>. Please check your inbox and click on the link to reset your password.";
        } else {
            return redirect()->back()->with('notify_error', 'Page not available');
        }

        return view('notify')
            ->with('title', $title)
            ->with('desc', $desc);
    }

    public function send_reset_password_link(Request $request)
    {

        $email = $request->email;
        $user = User::where('email', $email)->first();
        if ($user) {
            // Generate a password reset token
            $token = Str::random(60);

            // Store the token in the database or send it via email
            DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                ['token' => $token, 'created_at' => now()]
            );

            event(new SendResetPasswordLink($user, $token));

            return redirect()->route('notify', [
                'email' => $email,
                'type' => 'reset-password',
            ])->with('notify_success', 'A password reset link has been sent to your email.');
        }

        return redirect()->back()->with('notify_error', 'Email address not found.');
    }

    public function reset_password()
    {
        return view('reset-password')->with('notify_success', 'Reset Password');
    }

    public function set_new_password(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed', // Ensure password confirmation
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get the token and new password from the request
        $token = $request->input('token');
        $password = $request->input('password');

        // Find the email associated with the token
        $passwordReset = DB::table('password_resets')->where('token', $token)->first();

        if (! $passwordReset) {
            return redirect()->route('index')->with('notify_error', 'Invalid or expired token.');
        }

        // Find the user by email
        $user = User::where('email', $passwordReset->email)->first();

        if (! $user) {
            return redirect()->route('index')->with('notify_error', 'User not found.');
        }

        // Update the user's password using bcrypt
        $user->password = bcrypt($password);
        $user->save();

        // Delete the token from the password_resets table
        DB::table('password_resets')->where('token', $token)->delete();

        // Redirect with success message
        return redirect()->route('index')->with('notify_success', 'Password updated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->back()->with('notify_success', 'Logged Out!');
    }
}
