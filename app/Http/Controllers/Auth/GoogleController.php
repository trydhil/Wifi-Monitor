<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // If Google credentials are not set up in env, bypass to a beautiful mock login screen
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('auth.google.mock');
        }

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

    public function showMockPage()
    {
        // Get list of existing users to choose from for the mockup selector
        $users = User::all();
        return view('auth.google-mock', compact('users'));
    }

    public function handleMockLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
        ]);

        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'password' => Hash::make(Str::random(24)),
            ]
        );

        Auth::login($user, true);

        return redirect()->route('dashboard')->with('success', 'Berhasil masuk dengan Akun Google (Mock Mode)!');
    }
}
