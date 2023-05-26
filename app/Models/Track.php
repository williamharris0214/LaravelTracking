<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Track extends Model
{
    use HasFactory;
    protected $table = 'tracks';

    protected $primaryKey = 'id';

    public $incrementing = true;
    
    public $timestamps = false;

    protected $fillable = [
        'device_id',
        'device_name',
        'lat',
        'lon',
        'timestamp',
        'conf',
        'status',
    ];

    public function getBackgroundColor() {
        $now = Carbon::now();
        $datetime = Carbon::createFromTimestamp($this->timestamp);
        $diffInMinutes = $datetime->diffInMinutes($now);
        $res = 'bg-info';
        switch($this->status) {
            case '0':
                $res = 'bg-danger';
                break;
            case '1':
                $res = 'bg-warning';
                break;
            case '2':
                $res = 'bg-success';
                break;
            case '3':
                $res = 'bg-info';
                if($diffInMinutes >= 12 * 24 * 60) {
                    $res = 'bg-success';
                }
                break;
            default:
                break;
        }
        return $res;
    }

    public function dataFormatAttribute()
    {
        $now = Carbon::now();
        $datetime = Carbon::createFromTimestamp($this->timestamp);
        $diffInMinutes = $datetime->diffInMinutes($now);
        
        $res = $diffInMinutes . ' mins';
        if($diffInMinutes >= 60) {
            $mins = $diffInMinutes % 60;
            $hours = intval($diffInMinutes / 60);
            if($hours >= 24) {
                $days = intval($hours / 24);
                $hours = $hours % 24;
                if($hours == 0){
                    if($mins == 0)
                        $res = $days . ' days';
                    else
                        $res = $days . ' days ' . $mins . ' mins';
                }
                else {
                    if($mins == 0)
                        $res = $days . ' days ' . $hours . ' hours';
                    else
                        $res = $days . ' days ' . $hours . ' hours ' . $mins . ' mins';
                }
            }
            else {
                if($mins == 0)
                    $res = $hours . ' hours';
                else    
                    $res = $hours . ' hours ' . $mins . ' mins';
            }
        }
        return $res;
    }
}
