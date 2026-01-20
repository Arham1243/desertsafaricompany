<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the social platform authentication page.
     *
     * @param  string  $social
     * @return \Illuminate\Http\Response
     */
    public function index($social, Request $request)
    {
        // Store the intended URL in the session
        $request->session()->put('url.intended', url()->previous());

        return Socialite::driver($social)->stateless()->redirect();
    }

    /**
     * Obtain the user information from the social platform.
     *
     * @param  string  $social
     * @return \Illuminate\Http\Response
     */
    public function callback($social, Request $request)
    {
        try {
            $socialUser = Socialite::driver($social)->stateless()->user();

            $email = $socialUser->email ?? ($socialUser->nickname . '@' . $social . '.com');

            // Determine unique column for Apple
            $socialColumn = $social === 'apple' ? 'apple_id' : 'social_id';

            $existingUser = User::where($socialColumn, $socialUser->id)
                ->orWhere('email', $email)
                ->first();

            if ($existingUser) {
                $existingUser->update([
                    $socialColumn => $socialUser->id,
                    'signup_method' => $social,
                    'social_token' => $socialUser->token,
                    'avatar' => $socialUser->avatar ?? null,
                    'email_verified' => true,
                ]);

                Auth::login($existingUser);
                $redirectTo = $request->session()->pull('url.intended', route('frontend.index'));
                return redirect()->to($redirectTo)->with('notify_success', 'Login Successful!');
            } else {
                $user = User::create([
                    $socialColumn => $socialUser->id,
                    'signup_method' => $social,
                    'full_name' => $socialUser->name ?? $socialUser->nickname ?? 'Apple User',
                    'email' => $email,
                    'social_token' => $socialUser->token,
                    'avatar' => $socialUser->avatar ?? null,
                    'email_verified' => true,
                    'password' => bcrypt(uniqid()),
                ]);

                Auth::login($user);
                $redirectTo = $request->session()->pull('url.intended', route('frontend.index'));
                return redirect()->to($redirectTo)->with('notify_success', 'Signup Successful!');
            }
        } catch (Exception $e) {
            return redirect()->route('frontend.index')->with('notify_error', 'Failed to login using ' . ucfirst($social) . ': ' . $e->getMessage());
        }
    }
}
