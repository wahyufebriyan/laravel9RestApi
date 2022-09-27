<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $status['token'] = $user->createToken('MyApp')->plainTextToken;
            $status['name'] = $user->name;

            return $this->sendResponse($status, 'Login success.');
        }else {
            return $this->sendError('Unauthorized',['error' => 'Unauthorized']);
        }
    }
}
