<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function customer()
    {
        return $this->hasOne('App\Customer');
    }
    public function courier()
    {
        return $this->hasOne('App\Courier','id','courier_id');
    }
    public function city()
    {
        return $this->hasOne('App\City','id','city_id');
    }
    public function zone()
    {
        return $this->hasOne('App\Zone','id','zone_id');
    }
    public function products()
    {
        return $this->hasMany('App\OrderProducts','order_id','id');
    }
    public function notification()
    {
        return $this->hasOne('App\Notification','order_id','id')->latest();
    }
}
