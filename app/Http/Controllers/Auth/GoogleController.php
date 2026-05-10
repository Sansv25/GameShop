<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update user data if logged in previously with email only
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'avatar' => $googleUser->getAvatar(),
                ]);

                // Ensure email is marked as verified if they use google
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                }

            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'User',
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(24)), // Random secure password
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'avatar' => $googleUser->getAvatar(),
                ]);
                
                $user->email_verified_at = now();
                $user->save();
            }

            Auth::login($user, true);

            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login menggunakan Google. Silakan coba lagi.');
        }
    }
}
