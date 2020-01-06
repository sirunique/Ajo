<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Admin;
use App\User;
use App\Group;
use App\Member;
use App\Payment;

use Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private function response($status, $message){
        $res = array('res'=>$status, 'message'=>$message);
        return json_encode($res);
    }

    public function admin(){
        return view('admin.dashboard');
    }
    // public function add(){
    //     Admin::create([
    //         'email'=>'admin@gmail.com',
    //         'password'=> bcrypt('password')
    //     ]);
    // }

    public function login(){
        return view('admin.login');
    }

    public function postLogin(Request $request){
        // return $request->all();
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if(Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])){
            $request->session()->regenerate();
            return $this->response(true, ucwords('Login Successfull.....'));
        }
        return $this->response(false, ucwords('Invalid Login.....'));
    }

    public function logout(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }

    public function datacheckout(){
        $users = User::all();
        $thrifts = DB::table('groups')
            ->join('users', 'groups.admin_id', '=', 'users.id')
            ->select('groups.*', 'users.email')
            ->get();
            // return $thrifts;
        return view('admin.blank',['users'=>$users, 'thrifts'=>$thrifts]);
    }
}
