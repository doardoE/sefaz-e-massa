<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 3|rFEqqkcCiNSyF7Mclx18n9GXv0OoW56ylNYfzXSJdc2a9f8f
class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request){
        if(Auth::attempt($request->only(['email', 'password']))) {
            return $this->response('Autorizado!', 200, [
                'token' => Auth::user()->createToken('API Token', [
                    'arr-store',
                    'arr-update',
                    'arr-destroy'
                ])->plainTextToken
            ]);
        };
        return $this->response('Não autorizado!', 403);

    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->response('Token Revogado', 200);
    }

}
