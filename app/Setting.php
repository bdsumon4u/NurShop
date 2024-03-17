<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static function get($key)
    {
        $setting = new Setting();
        $entry = $setting->where('name', $key)->first();
        if (!$entry) {
            return;
        }
        return $entry->value;
    }

    public static function set($key, $value = null)
    {

        $entry = Setting::where('name', $key)->first();
        if($entry){
            $entry->value = $value;
            $entry->saveOrFail();
        }else{
            $entry = new Setting();
            $entry->name = $key;
            $entry->value = $value;
            $entry->save();
        }

    }
}
