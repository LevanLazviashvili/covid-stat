<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $fields = $request->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string',
        ]);

        $user = User::create([
            'name'      => Arr::get($fields, 'name'),
            'email'     => Arr::get($fields, 'email'),
            'password'  => bcrypt(Arr::get($fields, 'password'))
        ]);

        return Response([
           'user'   => $user,
           'token'  => $user->createToken('token')->plainTextToken
        ]);

    }

    public function signIn(Request $request)
    {
        $fields = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|string',
        ]);

        $user = User::where('email', Arr::get($fields, 'email'))->first();
        if (!$user || !Hash::check(Arr::get($fields, 'password'), $user->password)) {
            return response([
               'message'    => 'wrong email or password'
            ], 401);
        }

        return Response([
            'user'  => $user,
            'token' => $user->createToken('token')->plainTextToken
        ]);
    }
}
