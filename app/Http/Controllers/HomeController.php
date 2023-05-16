<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Device;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //return view('home');
        return redirect()->route('manage');
    }

    public function user_manage()
    {
        $users = User::get();
        $devices = Device::get();

        return view('user_manage', compact('users', 'devices'));
    }

    public function add_device(Request $request)
    {
        $data = $request->all();

        $user_id = $data['user_id'];
        $user = User::find($user_id);

        $user->devices = $data['devices'];
        $user->save();

        return $user;
    }
}