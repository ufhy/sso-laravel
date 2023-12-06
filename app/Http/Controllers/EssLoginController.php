<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class EssLoginController extends Controller
{
    public function redirect()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        return Socialite::driver('ess')->redirect();
    }

    public function callback()
    {
        try {
            $user = Socialite::driver('ess')->user();
            $findUser = User::query()->where('ess_id', $user->uid)->first();
            if ($findUser) {
                Auth::login($findUser);
                return redirect()->intended('/');
            }
            else {
                $newUser = User::query()->create([
                    'ess_id' => $user->uid,
                    'name' => $user->username,
                    'email' => $user->email,
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(5)),
                ]);
                Auth::login($newUser);
                return redirect()->intended('/');
            }
        }
        catch (\Exception $e) {
            Log::error($e);

            return response('Something went wrong!', 500);
        }
    }
}
