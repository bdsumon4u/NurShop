<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('admin.settings.index',compact('settings'));
    }
    public function store(Request $request)
    {
        $keys = $request->except('_token');


        if($request->imageID){
            Setting::set('site_logo', $request->imageID);
        }else{
            foreach ($keys as $key => $value){
                Setting::set($key, $value);
            }
        }

        return redirect('/admin/setting');
    }
}
