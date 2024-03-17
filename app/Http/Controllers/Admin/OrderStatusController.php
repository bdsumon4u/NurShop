<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.order-status.index');
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
            'statusName' => 'required'
        ]);

        $orderStatus = new OrderStatus();
        $orderStatus->statusName = $request['statusName'];
        $result = $orderStatus->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Order Status';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Order Status';
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
        $orderStatus['data'] = OrderStatus::latest()->get();
        return $orderStatus;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function edit($id)
    {
        $orderStatus = OrderStatus::find($id);
        return json_encode($orderStatus);
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
            'statusName' => 'required'
        ]);

        $orderStatus = new OrderStatus();
        $orderStatus->statusName = $request['statusName'];
        $result = $orderStatus->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Order Status';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Order Status';
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
        $result = OrderStatus::find($id)->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Order Status';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Order Status';
        }
        return json_encode($response);
    }
}
