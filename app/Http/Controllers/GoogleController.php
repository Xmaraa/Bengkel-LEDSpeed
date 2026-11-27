<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $g = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('login')->withErrors(['email' => 'Login Google gagal, coba lagi.']);
        }

        $user = User::where('google_id', $g->getId())
            ->orWhere('email', $g->getEmail())
            ->first();

        if ($user) {
            if (! $user->google_id) {
                $user->update(['google_id' => $g->getId()]);
            }
        } else {
            $user = User::create([
                'name' => $g->getName(),
                'email' => $g->getEmail(),
                'google_id' => $g->getId(),
                'password' => Str::random(40),
                'role' => 'pelanggan',
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if (! $user->isAdmin() && ! $user->phone) {
            return redirect()->route('phone.show');
        }

        return redirect()->intended(
            $user->isAdmin() ? route('admin.reservasi') : route('reservasi.create')
        );
    }
}