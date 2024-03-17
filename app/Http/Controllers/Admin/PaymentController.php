<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Payment;
use App\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.payment.index');
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
            'paymentTypeID' => 'required',
            'paymentNumber' => 'required'
        ]);

        $payment =  new Payment();
        $payment->payment_type_id = $request['paymentTypeID'];
        $payment->paymentNumber = $request['paymentNumber'];
        $result = $payment->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add payment ';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add payment';
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
        $payments['data'] = DB::table('payments')
            ->select( 'payments.*','payment_types.paymentTypeName')
            ->join('payment_types', 'payments.payment_type_id', '=', 'payment_types.id')
            ->latest('payments.created_at')->get();
        return json_encode($payments);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function edit($id)
    {
        $payments = DB::table('payments')
            ->select( 'payments.*','payment_types.paymentTypeName')
            ->join('payment_types', 'payments.payment_type_id', '=', 'payment_types.id')
            ->where('payments.id','=',$id)->first();

        return json_encode($payments);
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
            'paymentTypeID' => 'required',
            'paymentNumber' => 'required'
        ]);

        $payment = Payment::find($id);
        $payment->payment_type_id = $request['paymentTypeID'];
        $payment->paymentNumber = $request['paymentNumber'];
        $result = $payment->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add payment ';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add payment';
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
        $result = Payment::find($id)->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Payment';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Payment';
        }
        return json_encode($response);
    }

    public function paymentType(Request $request)
    {
        if(isset($request['q'])){
            $paymentTypes = PaymentType::query()->where([
                ['paymentTypeName', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active']
            ])->get();
        }else{
            $paymentTypes = PaymentType::query()->where('status', 'like', 'Active')->get();
        }
        $paymentType = array();
        foreach ($paymentTypes as $item) {
            $paymentType[] = array(
                "id" => $item['id'],
                "text" => $item['paymentTypeName']
            );
        }
        return json_encode($paymentType);

    }
    public function status(Request $request)
    {
        $payment = Payment::find($request['id']);
        $payment->status = $request['status'];
        $result = $payment->save();
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
