<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getUserInfo(Request $request, int $id): JsonResponse
    {
        $user = Auth::user();
        if(strtoupper($user->role) != "ADMIN")
        {
            return \response()->json($user,200);
        }
        else
        {
            $found = User::find($id);
            return \response()->json($found,200);
        }
    }
    public function getListUser(Request $request): JsonResponse
    {
        $user = Auth::user();
        if(strtoupper($user->role) != "ADMIN")
            return \response()->json($user,200);
        else{
            $list = User::all();
            return \response()->json($list,200);
        }
    }
    public function updateUserInfo(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = Auth::user();
        try {
            if(Auth::id() == $id || strtoupper($user->role) == "ADMIN")
            {
                $found = User::query()->where('id','=', $id)->first();
                foreach ($request as $key => $value){
                    if($key == 'password')
                    {
                        $found->$key = bcrypt($value) ;
                        continue;
                    }
                    $found->$key = $value;
                }
                $bool = $found->save();
                return response()->json( $bool, 200);
            }
            else
                throw new \Exception("Unauth",422);
        }catch (\Exception $exception){
            if($exception->getCode()==422)
                return response()->json( $exception->getMessage(), $exception->getCode());
            return response()->json( null, 500);
        }

    }

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
