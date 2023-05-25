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

    public function dataFormatAttribute()
    {
        $now = Carbon::now();
        $datetime = Carbon::createFromTimestamp($this->timestamp);
        $diffInMinutes = $datetime->diffInMinutes($now);
        
        $res = $diffInMinutes . ' Minutes';
        if($diffInMinutes >= 60) {
            $diffInMinutes = round($diffInMinutes / 60);
            if($diffInMinutes >= 24) {
                $diffInMinutes = round($diffInMinutes / 24);
                $res = $diffInMinutes . ' Days';
            }
            else {
                $res = $diffInMinutes . ' Hours';
            }
        }
        return $res;
    }
}
