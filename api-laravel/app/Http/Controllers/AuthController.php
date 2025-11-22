<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        if(Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Autorizado!',
                'data' => [
                    'token' => Auth::user()->createToken('API Token', [
                        'arr-store',
                        'arr-update',
                        'arr-destroy'
                    ])->plainTextToken
                ]
            ], 200);

        };
        return response()->json('Não autorizado!', 403);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json('Token Revogado', 200);
    }

    public function checkToken(Request $request)
    {
        return response()->json([
            'valid' => true,
        ], 200);
    }


}
