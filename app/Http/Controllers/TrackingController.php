<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Track;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
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
        $user = Auth::user();
        $devices = [];
        $temp_devices = json_decode($user->devices);
        foreach($temp_devices as $device) {
            $temp = Device::find($device);
            array_push($devices, $temp);
        }

        $tracks = Track::get();
        return view('tracking', compact('devices','tracks'));
    }

    public function dateChanged(Request $request) {
        $data = $request->all();
        
        $start = $data['start'];
        $end = $data['end'];
        $timestamp_start = strtotime($start);
        $timestamp_end = strtotime($end);

        $tracks = Track::whereBetween('timestamp',[$timestamp_start, $timestamp_end])->get();

        return $tracks;
    }
}
