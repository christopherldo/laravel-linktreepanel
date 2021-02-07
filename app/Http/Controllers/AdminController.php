<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'login',
            'loginAction',
            'register',
            'registerAction'
        ]);
    }

    public function index ()
    {
        echo 'Admin';
    }

    public function login (Request $request)
    {
        return view('admin.login', [
            'error' => $request->session()->get('error'),
        ]);
    }

    public function loginAction (Request $request)
    {
        $data = $request->only([
            'email',
            'password'
        ]);

        if(Auth::attempt($data)){
            return redirect()->route('admin.index');
        } else {
            $request->session()->flash('error', 'E-mail e/ou senha não conferem');

            return redirect()->route('login')->withInput([
                'email' => $data['email']
            ]);
        };
    }

    public function register ()
    {
        echo 'Register';
    }
}
