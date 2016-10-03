<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Support\Facades;
use Illuminate\Http\Request;
use Input;
use Image;

class UserController extends ClientController
{
    public function __construct() {
        parent::__construct();
    }

    public function register() {
        $this->data['page'] = 'register';

        return view('auth.register', $this->data);
    }

    public function login() {
        $this->data['page'] = 'login';

        return view('auth.login', $this->data);
    }

    public function enter(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/');
        } else {
            return redirect('/login')->with('login_error', 'Email or password does not match');
        }

    }

    public function save(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/');
        }  else {
            return redirect()->back();
        }
    }
}
