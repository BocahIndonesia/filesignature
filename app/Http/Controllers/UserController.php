<?php

namespace App\Http\Controllers;

use Illuminate\Http\{Request, Response, RedirectResponse};
use Illuminate\Support\Facades\{Hash, Auth};
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Mine\MyController;


class UserController extends MyController
{
    public function login(Request $request):RedirectResponse{
        $creditentials= $request->validate([
            'email'=> 'required',
            'password'=> 'required'
        ]);

        if(Auth::attempt($creditentials)){
            $request->session()->regenerate();
            return redirect()->intended('fuck');
        }
        // dump(Auth::user());
        return redirect()->intended('gagal');
    }
    // public function index(Request $request){
    //     return response('index');
    // }
    // public function register(Request $request){
    //     $fields= $request->validate([
    //         'name'=>'bail|required|string',
    //         'email'=>'bail|required|string|unique:users,email',
    //         'password'=>'required|string|confirmed'
    //     ]);

    //     $user= User::make([
    //         'name'=>$fields['name'],
    //         'email'=>$fields['email'],
    //         'password'=>bcrypt($fields['password'])
    //     ]);

    //     return new UserResource($user);
    // }

    // public function login(Request $request){
    //     return response('login');
    // }
    // public function logout(Request $request){
    //     return response('logout');
    // }
    // public function activateToken(Request $request, string $id){
    //     $user= User::findOrFail($id);
        
    // }
    // public function deactivateToken(Request $request, string $id){
    //     $user= User::findOrFail($id);
    // }
}
