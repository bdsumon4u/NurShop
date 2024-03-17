<?php

namespace App\Http\Controllers\Admin;

use App\Courier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.courier.index');
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
            'courierName' => 'required',
            'courierCharge' => 'required'
        ]);

        $courier = new Courier();
        $courier->courierName = $request['courierName'];
        $courier->hasCity = $request['hasCity'];
        $courier->hasZone = $request['hasZone'];
        $courier->courierCharge = $request['courierCharge'];
        $result = $courier->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Courier';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Courier';
        }
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $courier['data'] = Courier::latest()->get();
        return $courier;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function edit($id)
    {
        $courier = Courier::find($id);
        return json_encode($courier);
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
            'courierName' => 'required',
            'courierCharge' => 'required'
        ]);

        $courier =  Courier::find($id);
        $courier->courierName = $request['courierName'];
        $courier->hasCity = $request['hasCity'];
        $courier->hasZone = $request['hasZone'];
        $courier->courierCharge = $request['courierCharge'];
        $result = $courier->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Courier';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update Courier';
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
        $result = Courier::find($id)->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Courier';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Courier';
        }
        return json_encode($response);
    }
    public function status(Request $request)
    {
        $supplier = Courier::find($request['id']);
        $supplier->status = $request['status'];
        $result = $supplier->save();
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
