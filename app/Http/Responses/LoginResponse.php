<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();


        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;


        if ($role) {
            return redirect('/dashboard');
        }

        return redirect('/');
    }
}