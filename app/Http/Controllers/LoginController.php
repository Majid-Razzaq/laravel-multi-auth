<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    // This method will show login page for customer
    public function index(){
        return view('account.login');
    }

    public function authenticate(Request $request){
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
        else{
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return redirect()->route('account.dashboard');
            }else{
                return redirect()->route('account.login')->with('error','Either email or password is incorrect.');
                
            }
        }
    }


    public function register(){
        return view('account.register');
    }

    public function processRegister(Request $request){
        $rules = [
            'username' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
                return redirect()->route('account.register')->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->username;
        $user->email = $request->email; 
        $user->password = Hash::make($request->password);
        $user->role = 'customer';
        $user->save();

        return redirect()->route('account.login')->with('success','You have successfully registered.');
    }


    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }




}
