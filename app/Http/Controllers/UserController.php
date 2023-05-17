<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
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
        return view('user_manage');
    }

    public function user_manage()
    {
        $user_role = Auth::user()->role;
        if($user_role === 5) {
            $users = User::get();
            $devices = Device::get();
    
            return view('user_manage', compact('users', 'devices'));
        }
        else {
            return redirect()->route('home');
        }
    }

    public function add_device(Request $request)
    {
        $data = $request->all();

        $user_id = $data['user_id'];
        $user = User::find($user_id);

        $user->devices = !empty($data['devices']) ? $data['devices'] : [];
        $user->save();

        return $user;
    }
}
