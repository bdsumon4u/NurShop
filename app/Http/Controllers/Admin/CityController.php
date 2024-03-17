<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Courier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.city.index');
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
            'cityName' => 'required'
        ]);

        $city = new City();
        $city->courier_id = $request['courierID'];
        $city->cityName = $request['cityName'];
        $result = $city->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add City';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add City';
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
        $city['data'] = DB::table('cities')
            ->select( 'cities.*','couriers.courierName')
            ->join('couriers', 'cities.courier_id', '=', 'couriers.id')
            ->latest('cities.created_at')->get();;
        return json_encode($city);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function edit($id)
    {
        $city = DB::table('cities')
            ->select( 'cities.*','couriers.courierName')
            ->join('couriers', 'cities.courier_id', '=', 'couriers.id')
            ->where('cities.id','=',$id)->first();
        return json_encode($city);
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
            'cityName' => 'required'
        ]);

        $city = City::find($id);
        $city->courier_id = $request['courierID'];
        $city->cityName = $request['cityName'];
        $result = $city->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update City';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update City';
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
        $result = City::find($id)->delete();
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
    public function status(Request $request)
    {
        $city = City::find($request['id']);
        $city->status = $request['status'];
        $result = $city->save();
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
