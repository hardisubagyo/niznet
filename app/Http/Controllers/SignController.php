<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class SignController extends Controller
{

    public function attempt(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|exists:users,email',
            'password' => 'required',
        ]);

        $attempts = [
            'email' => $request->email,
            'password' => $request->password,
            'isdelete' => 0
        ];

        if (Auth::attempt($attempts, (bool) $request->remember)) {
            return redirect()->intended('/home');
        }

        return redirect()->back();
    }
}
