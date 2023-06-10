<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        return view('login');
    }

    public function signin(request $req){
        $req->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
        if(Auth()->attempt($req->only('email','password'))){
            $req->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        return "Wrong username or password";
    }
}
