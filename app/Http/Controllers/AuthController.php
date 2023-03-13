<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
//    public function registerUser(Request $request): JsonResponse
//    {
//        if(!$request->has("name") || !$request->has("email") || !$request->has("password" ) || !$request->has("retype_password") || $request->get("password" )!= $request->get("retype_password"))
//            return response()->json("Bad request!",Response::HTTP_BAD_REQUEST);
//        try {
//            $user = new User();
//            $user->name = $request->name;
//            $user->email = $request->email;
//            $user->password = bcrypt($request->password) ;
//            $user->save();
//            $res['name']=$user->name;
//            return response()->json($res, 200);
//        }
//        catch (\Exception $exception){
//            return response()->json("failed", 500);
//        }
//    }
    public function registerUser(RegisterRequest $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $res['name'] = $user->name;
        return response()->json($res, 200);
    }
}
