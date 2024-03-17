<?php

namespace App\Http\Controllers\Manager;

use App\Courier;
use App\Http\Controllers\Controller;
use App\OrderProducts;
use App\PaymentType;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\DataTables;

class Report extends Controller
{

    public function dateCourierUser()
    {
        return view('manager.report.dateCourierUser');
    }

    public function multipleDateCourierUser()
    {
        return view('manager.report.multipleDateCourierUser');
    }

    public function getMultipleDateCourierUser(Request $request)
    {

        $orders  = DB::table('orders')
            ->select('orders.*', 'customers.customerName', 'customers.customerPhone', 'customers.customerAddress', 'couriers.courierName', 'cities.cityName', 'zones.zoneName', 'users.name')
            ->join('customers', 'orders.id', '=', 'customers.order_id')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->leftJoin('cities', 'orders.city_id', '=', 'cities.id')
            ->leftJoin('zones', 'orders.zone_id', '=', 'zones.id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id');

        // if($request['startDate'] != '' && $request['endDate'] != ''){
        //     $orders = $orders->whereBetween('orders.deliveryDate', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
        // }

        if($request['startDate'] != '' && $request['endDate'] != ''){
            if($request['orderStatus'] == 'Delivered'){
                $orders = $orders->whereBetween('orders.deliveryDate', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
             }else if($request['orderStatus'] == 'Paid'){
                $orders = $orders->whereBetween('orders.completeDate', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
             }else if($request['orderStatus'] == 'Return'){
                $orders = $orders->whereBetween('orders.completeDate', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
            }else{
                $orders = $orders->whereBetween('orders.orderDate', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
            }
        }

        if($request['courierID'] != ''){
            $orders = $orders->where('orders.courier_id','=',$request['courierID']);
        }
        if($request['orderStatus'] != 'All'){
            $orders = $orders->where('orders.status','like',$request['orderStatus']);
        }
        if($request['userID'] != ''){
            $orders = $orders->where('orders.user_id','=',$request['userID']);
        }
        $orders = $orders->latest()->get();
        $order['data'] = $orders->map(function ($order) {
            $products = DB::table('order_products')->select('order_products.*')->where('order_id', '=', $order->id)->get();
            $orderProducts = '';
            foreach ($products as $product) {
                $orderProducts = $orderProducts . $product->quantity.' x '. $product->productName . '<br>';
            }
            $order->products = rtrim($orderProducts, '<br>');
            return $order;
        });
        return json_encode($order);

    }

    public function dateCourier()
    {
        return view('manager.report.dateCourier');
    }

    public function getDateCourier(Request $request)
    {
        $response = [];
        if($request['courierID'] == ''){
            $couriers = Courier::all();
            foreach ($couriers as $courier){
                $temp['courier'] = $courier->courierName;
                $temp['date'] = $request['startDate'].' to '.$request['endDate'];
                $temp['all'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'');
                $temp['processing'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Processing');
                $temp['pendingPayment'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Payment Pending');
                $temp['onHold'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'On Hold');
                $temp['canceled'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Canceled');
                $temp['invoiced'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Invoiced');
                $temp['stockOut'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Stock Out');
                $temp['delivered'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Delivered');
                $temp['paid'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Paid');
                $temp['paidAmount'] = $this->getStatusDateCourierAmount($request['startDate'],$request['endDate'],$courier->id,'Paid');
                $temp['return'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Return');
                array_push($response,$temp);
            }
        }else{
            $courier = Courier::find($request['courierID']);
            $temp['courier'] = $courier->courierName;
            $temp['date'] = $request['startDate'].' to '.$request['endDate'];
            $temp['all'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'');
            $temp['processing'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Processing');
            $temp['pendingPayment'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Payment Pending');
            $temp['onHold'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'On Hold');
            $temp['canceled'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Canceled');
            $temp['invoiced'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Invoiced');
            $temp['stockOut'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Stock Out');
            $temp['delivered'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Delivered');
            $temp['paid'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Paid');
            $temp['paidAmount'] = $this->getStatusDateCourierAmount($request['startDate'],$request['endDate'],$courier->id,'Paid');
            $temp['return'] = $this->getStatusDateCourier($request['startDate'],$request['endDate'],$courier->id,'Return');
            array_push($response,$temp);
        }
        $result['data'] = $response;
        return json_encode($result);
    }

    public function getStatusDateCourier($startDate,$endDate,$courierID,$status)
    {
        $orders  = DB::table('orders')
            ->select('orders.*','couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id');
        $orders = $orders->where('orders.courier_id','=',$courierID);

        if($startDate != '' && $endDate != ''){
            $orders = $orders->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59']);
        }

        if(!empty($status)){
            if($status == 'Completed'){
                $orders = $orders->Where('orders.status','=',$status)->orWhere('orders.status','=','Pending Invoiced');
            }else{
                $orders = $orders->Where('orders.status','=',$status);
            }
        }
        return $orders->get()->count();
    }

    public function getStatusDateCourierAmount($startDate,$endDate,$courierID,$status)
    {
        $orders  = DB::table('orders')
            ->select('orders.*','couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id');
        $orders = $orders->where('orders.courier_id','=',$courierID);

        if($startDate != '' && $endDate != ''){
            $orders = $orders->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59']);
        }
        if(!empty($status)){
            if($status == 'Completed'){
                $orders = $orders ->whereIn('orders.status', ['Completed', 'Pending Invoiced']);
            }else{
                $orders = $orders->Where('orders.status','=',$status);
            }
        }
        return $orders->get()->sum('subTotal');
    }

    public function dateUser()
    {
        return view('manager.report.dateUser');
    }

    public function getDateUser(Request $request)
    {
        $response = [];
        if($request['userID'] == ''){
            $users = User::all();
            foreach ($users as $user){
                $temp['name'] = $user->name;
                $temp['date'] = $request['startDate'].' to '.$request['endDate'];
                $temp['all'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'');
                $temp['processing'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Processing');
                $temp['pendingPayment'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Payment Pending');
                $temp['onHold'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'On Hold');
                $temp['canceled'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Canceled');
                $temp['completed'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Completed');

                $temp['invoiced'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Invoiced');
                $temp['stockOut'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Stock Out');
                $temp['delivered'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Delivered');
                $temp['paid'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Paid');
                $temp['paidAmount'] = $this->getStatusDateUserAmount($request['startDate'],$request['endDate'],$user->id,'Paid');
                $temp['return'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Return');
                array_push($response,$temp);
            }
        }else{
            $user = User::find($request['userID']);
            $temp['name'] = $user->name;
            $temp['date'] = $request['startDate'].' to '.$request['endDate'];
            $temp['all'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'');
            $temp['processing'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Processing');
            $temp['pendingPayment'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Payment Pending');
            $temp['onHold'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'On Hold');
            $temp['canceled'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Canceled');
            $temp['completed'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Completed');

            $temp['invoiced'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Invoiced');
            $temp['stockOut'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Stock Out');
            $temp['delivered'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Delivered');
            $temp['paid'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Paid');
            $temp['paidAmount'] = $this->getStatusDateUserAmount($request['startDate'],$request['endDate'],$user->id,'Paid');
            $temp['return'] = $this->getStatusDateUser($request['startDate'],$request['endDate'],$user->id,'Return');
            array_push($response,$temp);
        }
        $result['data'] = $response;
        return json_encode($result);
    }

    public function getStatusDateUser($startDate,$endDate,$userID,$status)
    {
        $orders  = DB::table('orders')
            ->select('orders.*','couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id');
        $orders = $orders->where('orders.user_id','=',$userID);

        if($startDate != '' && $endDate != ''){
            $orders = $orders->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59']);
        }
        if(!empty($status)){
            if($status == 'Completed'){
                $orders = $orders->Where('orders.status','=',$status)->orWhere('orders.status','=','Pending Invoiced');
            }else{
                $orders = $orders->Where('orders.status','=',$status);
            }
        }
        return $orders->get()->count();
    }

    public function getStatusDateUserAmount($startDate,$endDate,$userID,$status)
    {
        $orders  = DB::table('orders')
            ->select('orders.*','couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id');
        $orders = $orders->where('orders.user_id','=',$userID);

        if($startDate != '' && $endDate != ''){
            $orders = $orders->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59']);
        }
        if(!empty($status)){
            if($status == 'Completed'){
                $orders = $orders->Where('orders.status','=',$status)->orWhere('orders.status','=','Pending Invoiced');
            }else{
                $orders = $orders->Where('orders.status','=',$status);
            }
        }
        return $orders->get()->sum('subTotal');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getOrdersOnDateCourierUser(Request $request)
    {
        $orders  = DB::table('orders')
            ->select('orders.*', 'customers.customerName', 'customers.customerPhone', 'customers.customerAddress', 'couriers.courierName', 'cities.cityName', 'zones.zoneName', 'users.name')
            ->join('customers', 'orders.id', '=', 'customers.order_id')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->leftJoin('cities', 'orders.city_id', '=', 'cities.id')
            ->leftJoin('zones', 'orders.zone_id', '=', 'zones.id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id');

        if($request['date'] != ''){
            if($request['orderStatus'] == 'Delivered'){
                $orders = $orders->where('orders.deliveryDate','like',$request['date']);
            }else if($request['orderStatus'] == 'Paid'){
                $orders = $orders->where('orders.completeDate','like',$request['date']);
            }else if($request['orderStatus'] == 'Return'){
                $orders = $orders->where('orders.completeDate','like',$request['date']);
            }else{
                $orders = $orders->where('orders.updated_at','like','%'.$request['date'].'%');
            }
        }
        if($request['courierID'] != ''){
            $orders = $orders->where('orders.courier_id','=',$request['courierID']);
        }
        if($request['orderStatus'] != 'All'){
            $orders = $orders->where('orders.status','like',$request['orderStatus']);
        }
        if($request['userID'] != ''){
            $orders = $orders->where('orders.user_id','=',$request['userID']);
        }
        $orders = $orders->latest()->get();
        $order['data'] = $orders->map(function ($order) {
            $products = DB::table('order_products')->select('order_products.*')->where('order_id', '=', $order->id)->get();
            $orderProducts = '';
            foreach ($products as $product) {
                $orderProducts = $orderProducts . $product->quantity.' x '. $product->productName . '<br>';
            }
            $order->products = rtrim($orderProducts, '<br>');
            return $order;
        });
       return json_encode($order);
    }

    public function users(Request $request)
    {
        if(isset($request['q'])){
            $users = User::query()->where('name','like','%'.$request['q'].'%')->get();
        }else{
            $users = User::all();
        }
        $user = array();
        foreach ($users as $item) {
            $user[] = array(
                "id" => $item['id'],
                "text" => $item['name']
            );
        }
        return json_encode($user);
    }
    public function product(){
        return view('manager.report.product');
    }
    public function getProduct(Request $request){

         $status  = $request->input('orderStatus');

        $orders = DB::table('orders')
            ->join('order_products', 'orders.id', '=', 'order_products.order_id')
            ->select('orders.status','orders.orderDate','order_products.*', DB::raw('SUM(quantity) as total_amount'))
            ->groupBy('order_products.product_id');

            if($request['startDate'] != '' && $request['endDate'] != ''){
                if($request['orderStatus'] == 'Delivered'){
                    $orders = $orders->whereBetween('orders.deliveryDate', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
                 }else if($request['orderStatus'] == 'Paid'){
                    $orders = $orders->whereBetween('orders.completeDate', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
                 }else if($request['orderStatus'] == 'Return'){
                    $orders = $orders->whereBetween('orders.completeDate', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
                }else{
                    if($request['orderStatus'] =! 'Pending Invoiced' && $request['orderStatus'] =! 'Invoiced'){
                        $orders = $orders->whereBetween('orders.orderDate', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
                    }
                }
            }

            if($status != 'All' && $status != 'Pending Invoiced'){
                $orders  = $orders->where('orders.status', 'like', $status);
            }
            if($status == 'Pending Invoiced'){
                $orders = $orders ->whereIn('orders.status', ['Completed', 'Pending Invoiced']);
                // $orders  = $orders->where('orders.status', 'like', $status);
            }
            
            if($request['courierID'] != ''){
                $orders = $orders->where('orders.courier_id','=',$request['courierID']);
            }

            return DataTables::of($orders)->make();

    }
    public function payment()
    {

        return view('manager.report.payment');
    }
    public function getPayment(Request $request)
    {
        $orders = DB::table('payment_compeltes')
            ->join('payments', 'payment_compeltes.payment_id', '=', 'payments.id')
            ->join('users', 'payment_compeltes.userID', '=', 'users.id')
            ->join('payment_types', 'payment_compeltes.payment_type_id', '=', 'payment_types.id');

            if($request['startDate'] != '' && $request['endDate'] != ''){
                $orders = $orders->whereBetween('payment_compeltes.date', [$request['startDate'].' 00:00:00',$request['endDate'].' 23:59:59']);
            }
            if($request['userID'] != ''){
                $orders = $orders->where('payment_compeltes.userID','=',$request['userID']);
            }
            if($request['paymentID'] != ''){
                $orders = $orders->where('payment_compeltes.payment_id','=',$request['paymentID']);
            }
            if($request['paymentTypeID'] != ''){
                $orders = $orders->where('payment_compeltes.payment_type_id','=',$request['paymentTypeID']);
            }
        return DataTables::of($orders)->make();
    }
    public function paymentID(Request $request)
    {
        if(isset($request['q'])){
            $users = Payment::query()->where('name','like','%'.$request['q'].'%')->get();
        }else{
            $users = Payment::all();
        }
        $user = array();
        foreach ($users as $item) {
            $user[] = array(
                "id" => $item['id'],
                "text" => $item['paymentNumber']
            );
        }
        return json_encode($user);
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

}
