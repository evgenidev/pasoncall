<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Record;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
$this->middleware('forceSSL');        
$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        if (Auth::user()->type==2){
        $records = Record::where('status','pending')->get();}
        elseif(Auth::user()->type==1){
            $records = Record::where('user_id',Auth::user()->id)->latest()->where('status','!=','solved')->where('status','!=','canceled')->get();
        }
        return view('home', ['users' => $users,'records'=>$records]);
    }
}
