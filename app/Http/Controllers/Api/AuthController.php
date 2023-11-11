<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request){

        $user = User::where('phone', $request->phone)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('user-token')->plainTextToken;

        return (new AuthResource($user))
            ->additional(['meta' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ]]);
    }


    public function register(RegisterRequest $request){

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => bcrypt($request->input('password')),
        ]);



        $token = $user->createToken('user-token')->plainTextToken;

        return (new AuthResource($user))
            ->additional(['meta' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ]]);
    }
}
