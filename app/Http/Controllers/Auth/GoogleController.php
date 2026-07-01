<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists by email, if not create
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    // Set random password if new user
                    'password' => Hash::make(Str::random(24)),
                ]
            );

            Auth::login($user, true);

            return redirect()->route('dashboard')->with('success', 'Berhasil masuk dengan Google!');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal autentikasi via Google.']);
        }
    }
}
