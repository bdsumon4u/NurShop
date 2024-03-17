<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.payment-type.index');
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
            'paymentTypeName' => 'required'
        ]);

        $paymentType =  new PaymentType();
        $paymentType->paymentTypeName = $request['paymentTypeName'];
        $result = $paymentType->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add payment Type ';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add payment Type';
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
        $courier['data'] = PaymentType::latest()->get();
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
        $paymentType = PaymentType::find($id);
        return json_encode($paymentType);
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
            'paymentTypeName' => 'required'
        ]);

        $paymentType =  PaymentType::find($id);
        $paymentType->paymentTypeName = $request['paymentTypeName'];
        $result = $paymentType->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update payment Type ';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update payment Type';
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
        $result = PaymentType::find($id)->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Payment Type';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Payment Type';
        }
        return json_encode($response);
    }
    public function status(Request $request)
    {
        $paymentType = PaymentType::find($request['id']);
        $paymentType->status = $request['status'];
        $result = $paymentType->save();
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
