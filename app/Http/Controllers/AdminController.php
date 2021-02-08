<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function login (Request $request)
    {
        return view('admin.login', [
            'errors' => $request->session()->get('errors'),
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
            $request->session()->flash('errors', ['login' => 'E-mail e/ou senha não conferem']);

            return redirect()->route('login')->withInput([
                'email' => $data['email']
            ]);
        };
    }

    public function register (Request $request)
    {
        return view('admin.register', [
            'errors' => $request->session()->get('errors'),
        ]);
    }

    public function registerAction (Request $request)
    {
        $data = $request->only([
            'email',
            'password',
            'password_confirmation'
        ]);

        $validator = Validator::make($data, [
            'email' =>  'required|string|email|unique:users|max:50',
            'password' => 'required|string|confirmed|min:8'
        ]);

        if($validator->fails()){
            $request->session()->flash('errors', $validator->errors()->all());

            return redirect()->route('admin.register')->withInput([
                'email' => $data['email']
            ]);
        } else {
            $email = $data['email'];
            $hash = password_hash($data['password'], PASSWORD_DEFAULT);

            $newUser = new User();

            do {
                $userPublicId = Str::uuid()->toString();
            } while(User::where('public_id', $userPublicId)->count() > 0);

            $newUser->public_id = $userPublicId;
            $newUser->email = $email;
            $newUser->password = $hash;
            $newUser->save();

            Auth::login($newUser);

            return redirect()->route('admin.index');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('admin.index');
    }

    public function index ()
    {
        $user = Auth::user();

        $pages = Page::where('id_user', $user->public_id)->get();

        echo view('admin.index', [
            'pages' => $pages
        ]);
    }

    public function pageLinks (string $slug)
    {
        return view('admin.page_links');
    }

    public function pageDesign (string $slug)
    {
        return view('admin.page_design');
    }

    public function pageStats (string $slug)
    {
        return view('admin.page_stats');
    }
}
