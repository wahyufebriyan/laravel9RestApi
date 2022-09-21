<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validator error', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $status['token'] = $user->createToken('MyApp')->plainTextToken;
        $status['name'] = $user->name;

        return $this->sendResponse($status, 'Register success.');
    }

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
