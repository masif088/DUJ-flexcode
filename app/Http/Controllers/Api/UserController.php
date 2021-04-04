<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Services\User\UserService;

class UserController extends Controller
{
    public function __construct()
    {
        $this->log = new LogController;
    }
    function login(Request $request)
    {
        $user = User::where(function($z){
            $z->where('role','ketua')->orWhere('role','checker')->orWhere('role','monitor');;
        })->where('email', $request->email)->first();
        // print_r($data);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'msg' => 'Akun anda tidak ditemukan',
                'status' => 'error',
            ], 401);
        }
        $user->update([
            'token' => $request->token
        ]);
        $token = $user->createToken('token-' . $user->name)->plainTextToken;
        $this->log->create('melakukan Login #'.$user->name,'user',$user->id);

        $response = [
            'data' => $user,
            'token' => $token,
            'status' => 'ok',
        ];

        return response()->json($response, 201);
    }
    public function isAuth()
    {

        $status = null;
        $data=null;
        $statuscode = null;
        if (auth('sanctum')->check()) {
            $status = 'ok';
            $data = auth('sanctum')->user();
            $statuscode = 200;
        } else {
            $status = 'unauth';
            $statuscode = 401;
        }
        return response()->json([
            'status' => $status,
            'data' => $data,
            'msg' => $statuscode == 200 ? 'ok' : 'error',
        ], $statuscode);
    }
    public function store(StoreUserRequest $request)
    {
        if(isset($request->validator) && $request->validator->fails()){
            return response()->json([
                'status' => 'error',
                'data' => $request->validator->messages(),
            ],400);
        }
        $request['role'] = 'checker';
        $request['gudang'] = auth('sanctum')->user()->gudang_id;
        $user = UserService::store($request);
        $this->log->create('membuat user baru #'.$user->name,'user',$user->id);

        return response()->json([
            'status' => 'ok',
            'data' => $user,
        ],201);
    }
    public function update(UpdateRequest $request,User $id)
    {
        if(isset($request->validator) && $request->validator->fails()){
            return response()->json([
                'status' => 'error',
                'data' => $request->validator->messages(),
            ],400);
        }
        if(auth('sanctum')->user()->role == 'admin'){
            if(!in_array($request->role,['admin','head','teknisi'])){
                return response()->json([
                    'status' => 'error',
                    'msg' => 'role yang anda maskan tidak sesuai',
                ],404);
            }
        }elseif(auth('sanctum')->user()->role == 'head'){
            if(!in_array($request->role,['ketua','head'])){
                return response()->json([
                    'status' => 'error',
                    'msg' => 'role yang anda maskan tidak sesuai',
                ],404);
            }
        }elseif(auth('sanctum')->user()->role == 'ketua'){
            if(!in_array($request->role,['ketua','checker'])){
                return response()->json([
                    'status' => 'error',
                    'msg' => 'role yang anda maskan tidak sesuai',
                ],404);
            }
        }elseif(auth('sanctum')->user()->role == 'checker'){
            if($request->role != 'checker'){
                return response()->json([
                    'status' => 'error',
                    'msg' => 'role yang anda maskan tidak sesuai',
                ],404);
            }
        }elseif(auth('sanctum')->user()->role == 'teknisi'){
            if($request->role != 'teknisi'){
                return response()->json([
                    'status' => 'error',
                    'msg' => 'role yang anda maskan tidak sesuai',
                ],404);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'msg' => 'role yang anda maskan tidak sesuai',
                ],404);
        }
        UserService::edit($request,$id);
        return response()->json([
            'status' => 'ok',
        ],201);
    }
}
