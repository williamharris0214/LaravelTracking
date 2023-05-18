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

    public function getDeviceNames() {
        $device_names = [];
        $tracks = Track::select('device_name')->groupBy('device_name')->get();
        foreach($tracks as $track) {
            array_push($device_names, $track->device_name);
        }
        return $device_names;
    }

    public function addData(Request $request) {
        $data = $request->all();
        $track_datas = $data['track_data'];
        foreach($track_datas as $track_data) {
            $device_names = $this->getDeviceNames();
            
            $device_name = $track_data['device_name'];
            $lat = $track_data['lat'];
            $lon = $track_data['lon'];
            $timestamp = $track_data['timestamp'];
            $conf = $track_data['conf'];
            $status = $track_data['status'];

            $track = new Track;
            $track->device_name = $device_name;
            $track->lat = $lat;
            $track->lon = $lon;
            $track->timestamp = $timestamp;
            $track->conf = $conf;
            $track->status = $status;

            if(in_array($device_name, $device_names)) {
                $track->device_id = $this->getDeviceId($device_name);
            }
            else {
                $device = new Device;
                $device->device_name = $device_name;
                $device->save();
                $track->device_id = $device->id;
            }

            $track->save();
        }
        return 'asdf';
    }

    public function getDeviceId($device_name) {
        $device = Device::where('device_name', $device_name)->first();
        if($device) 
            return $device->id;
        else
            return null;
    }
}