<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Courier;
use App\Http\Controllers\Controller;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.zone.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return false|string
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'courierID' => 'required',
            'cityID' => 'required',
            'zoneName' => 'required'
        ]);

        $zone = new Zone();
        $zone->courier_id = $request['courierID'];
        $zone->city_id = $request['cityID'];
        $zone->zoneName = $request['zoneName'];
        $result = $zone->save();
        if ($result) {

            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Zone';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Zone';
        }
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function show($id)
    {
        $zone['data'] = DB::table('zones')
            ->select('zones.*', 'cities.cityName', 'couriers.courierName')
            ->join('couriers', 'zones.courier_id', '=', 'couriers.id')
            ->join('cities', 'zones.city_id', '=', 'cities.id')
            ->latest('zones.created_at')->get();
        return json_encode($zone);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function edit($id)
    {
        $zone['data'] = DB::table('zones')
            ->select('zones.*', 'cities.cityName', 'couriers.courierName')
            ->join('couriers', 'zones.courier_id', '=', 'couriers.id')
            ->join('cities', 'zones.city_id', '=', 'cities.id')
            ->where('zones.id','=',$id)->first();

        return json_encode($zone);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return false|string
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'courierID' => 'required',
            'cityID' => 'required',
            'zoneName' => 'required'
        ]);

        $zone =  Zone::find($id);
        $zone->courier_id = $request['courierID'];
        $zone->city_id = $request['cityID'];
        $zone->zoneName = $request['zoneName'];
        $result = $zone->save();
        if ($result) {

            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Zone';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Zone';
        }
        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return false|string
     */
    public function destroy($id)
    {
        $result =  Zone::find($id)->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete City';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete City';
        }
        return json_encode($response);
    }
    public function courier(Request $request)
    {
        if(isset($request['q'])){
            $couriers = Courier::query()->where('courierName','like','%'.$request['q'].'%')->get();
        }else{
            $couriers = Courier::all();
        }
        $courier = array();
        foreach ($couriers as $item) {
            $courier[] = array(
                "id" => $item['id'],
                "text" => $item['courierName']
            );
        }
        return json_encode($courier);
    }
    public function city(Request $request)
    {
        if (isset($request['q']) && $request['courierID']) {
            $cites = City::query()->where([
                ['cityName', 'like', '%' . $request['q'] . '%'],
                ['courier_id', '=',  $request['courierID']]
            ])->get();
        } else {
            $cites = City::query()->where( 'courier_id', '=',  $request['courierID'] )->get();
        }
        $city = array();
        foreach ($cites as $item) {
            $city[] = array(
                "id" => $item['id'],
                "text" => $item['cityName']
            );
        }
        return json_encode($city);
    }
    public function status(Request $request)
    {
        $zone = Zone::find($request['id']);
        $zone->status = $request['status'];
        $result = $zone->save();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Status to '.$request['status'];
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to update Status '.$request['status'];
        }
        return json_encode($response);
    }
}
