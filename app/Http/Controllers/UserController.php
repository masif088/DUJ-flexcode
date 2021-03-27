<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Gudang;
use App\Models\User;
use Illuminate\Http\Request;
use Services\User\UserService;

class UserController extends Controller
{
    public function __construct()
    {
        $this->log = new LogController;

    }
    public function index()
    {
        $user = auth()->user();
        $gudang = Gudang::get();
        return view('user.profil',compact(['user','gudang']));
    }
    public function all()
    {
        $user = User::where('id' ,'!=',auth()->user()->id)->get();
        return view('user.list',compact('user'));
    }
    public function create()
    {
        $gudang = Gudang::get();
        return view('user.create',compact('gudang'));
    }
    public function edit(User $id)
    {
        $user = $id;
        if(auth()->user()->role != 'admin'){
            $user = auth()->user();
        }
        $gudang = Gudang::get();
        return view('user.profil',compact(['user','gudang']));
    }
    public function store(StoreUserRequest $request)
    {
//        if(isset($request->validator) && $request->validator->fails()){
//            $request->flash();
//            toastr()->warning('silahkan cek kembali');
//            return redirect()->back()->withErrors($request->validator->messages());
//        }
//        if(auth()->user()->role == 'admin'){
//            if(!in_array($request->role,['admin','head','teknisi'])){
//                dd('haloo');
//                return redirect()->back();
//            }
//        }elseif(auth()->user()->role == 'head'){
//            if($request->role != 'ketua'){
//                return redirect()->back();
//            }
//        }elseif(auth()->user()->role == 'ketua'){
//            if($request->role != 'checker'){
//                return redirect()->back();
//            }
//        }else{
//            return redirect()->back();
//        }
//        $ss = UserService::store($request);
//        $this->log->create('membuat akun'.$ss->role.' baru #'.$ss->name,'user',$ss->id);
//
//        toastr()->success('berhasil membuat');
//        return redirect()->back();
        if(isset($request->validator) && $request->validator->fails()){
            $request->flash();
            toastr()->warning('silahkan cek kembali');
            return redirect()->back()->withErrors($request->validator->messages());
        }
        if(auth()->user()->role == 'admin'){
            if(!in_array($request->role,['admin','head','teknisi'])){
                dd('haloo');
                return redirect()->back();
            }
        }elseif(auth()->user()->role == 'head'){
            if($request->role != 'ketua'){
                return redirect()->back();
            }
        }elseif(auth()->user()->role == 'ketua'){
            if($request->role != 'checker'){
                return redirect()->back();
            }
        }else{
            return redirect()->back();
        }
        $fileName = null;
        if ($request->file('img') != null) {
            $file = $request->file('img');
            $fileName = substr(md5(microtime()), 0, 100).'.'.$file->getClientOriginalExtension();
            $request->file('img')->storeAs('public/user/',$fileName);
        }
        $data = [
            "action" => 'user.store',
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'img' => $fileName,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'sidik' => $request->sidik,
            'gudang_id' => $request->gudang,
        ];
//        dd($data);
        return view('fingers.index', compact('data'));
    }
    public function update(UpdateRequest $request,User $id)
    {
        if(isset($request->validator) && $request->validator->fails()){
            $request->flash();
            return redirect()->back()->withErrors($request->validator->messages());
        }
        if(auth()->user()->role != 'admin'){
            return redirect()->back();

        }
        $fileName = null;
        if ($request->file('img') != null) {
            $file = $request->file('img');
            $fileName = substr(md5(microtime()), 0, 100).'.'.$file->getClientOriginalExtension();
            $request->file('img')->storeAs('public/user/',$fileName);
        }
        $data=[
            "action" => 'user.update',
            'name' => $request->name,
            'email' => $request->email,
            'img' => $fileName,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'sidik' => $request->sidik,
            'gudang_id' => $request->gudang,
            'password' => (bcrypt($request->password))?bcrypt($request->password):null
        ];
        return view('fingers.index', compact('data'));
    }
}
