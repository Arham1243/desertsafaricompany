<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ImageTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user) {
            $token = Str::random(60);

            DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                ['token' => $token, 'created_at' => now()]
            );

            $data = [
                'full_name' => $user->full_name,
                'verify_link' => route('password.reset', ['token' => $token]),
                'logo' => asset(ImageTable::where('table_name', 'logo')->latest()->first()->img_path ?? 'frontend/assets/images/logo (1).webp'),
            ];

            Mail::send('emails.reset-password', ['data' => $data], function ($message) use ($user) {
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message->to($user->email)
                    ->subject('Password Reset - '.env('MAIL_FROM_NAME'));
            });

            return response()->json([
                'status' => 'success',
                'message' => 'A password reset link has been sent to your email.',
                'redirect_url' => route('notify', [
                    'email' => $email,
                    'type' => 'reset-password',
                ]),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email address not found.',
            'redirect_url' => url()->previous(),
        ]);
    }

    public function showResetForm()
    {
        return view('reset-password')->with('notify_success', 'Reset Password');
    }

    public function resetPassword(Request $request)
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
}
