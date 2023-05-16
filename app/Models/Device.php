<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'device_name'
    ];

    public function tracks()
    {
        return $this->hasMany(Track::class, 'device_id', 'id');
    }

    public function latest_track() {
        return $this->hasMany(Track::class, 'device_id', 'id')->latest('timestamp');
    }
}

