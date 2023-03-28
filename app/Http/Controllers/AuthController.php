<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function registerUser(RegisterRequest $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->name;
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = 'user';
        $user->password = bcrypt($request->password);
        $user->save();
        $res['user_name'] = $user->user_name;
        return response()->json($res, 200);
    }

    public function login(Request $request): JsonResponse
    {
        $data = request(['email','password']);
        if(Auth::attempt($data)){
            $user = Auth::user();
            $res = [];
            $res['token']=$user->createToken('personal_access_tokens')->accessToken;
            $res['role']=$user->role;
            $res['name']=$user->name;
            return response()->json($res, Response::HTTP_OK);
        }
        return response()->json("Sai tên đăng nhập hoặc mật khẩu", Response::HTTP_UNAUTHORIZED);
    }
    public function doLogin(Request $request)
    {

        $data= [
          'email'=>$request->get('email'),
        'password'=>$request->get('password')
        ];
        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect('/carts');
        }
        return redirect('/login');
    }

    public function showLogin(): JsonResponse
    {
        return \response()->json('please login',403);
    }
}
