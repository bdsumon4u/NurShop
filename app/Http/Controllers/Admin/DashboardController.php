<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function getData(Request $request)
    {
        $date = explode(' to ',$request['date']);
        $startDate = $date[0];
        if(isset($date[1])){
            $endDate = $date[1];
        }else{
            $endDate = $date[0];
        }
        $response['revenue'] = DB::table("orders")->where('orders.status','like','Paid')->sum("subTotal");
        $response['allOrders'] = DB::table('orders')->count();
        $response['all'] = DB::table('orders')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['processing'] = DB::table('orders')->where('status','like','Processing')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['pendingPayment'] = DB::table('orders')->where('status','like','Payment Pending')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['onHold'] = DB::table('orders')->where('status','like','On Hold')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['canceled'] = DB::table('orders')->where('status','like','Canceled')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['completed'] = DB::table('orders')->where('status','like','Completed')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['pendingInvoiced'] = DB::table('orders')->where('status','like','Completed')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->orWhere('orders.status', 'like', 'Pending Invoiced')->count();
        $response['invoiced'] = DB::table('orders')->where('status','like','Invoiced')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['stockOut'] =  DB::table('orders')->where('status','like','Stock Out')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['delivered'] = DB::table('orders')->where('status','like','Delivered')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['customerConfirm'] =  DB::table('orders')->where('status','like','Customer Confirm')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['paid'] = DB::table('orders')->where('status','like','Paid')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['return'] = DB::table('orders')->where('status','like','Return')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['lost'] = DB::table('orders')->where('status','like','Lost')->whereBetween('orders.orderDate', [$startDate.' 00:00:00',$endDate.' 23:59:59'])->count();
        $response['store'] =  DB::table("stores")->get()->count();
        $response['user'] = DB::table("users")->where('role_id','=','3')->get()->count();
        $response['status'] = 'success';
         return json_encode($response);
        die();

    }

    public function stockOutProduct()
    {
        $orders  = DB::table('orders')
            ->leftjoin('order_products', 'orders.id', '=', 'order_products.order_id')
            ->select('order_products.*')->where('status','like','Stock Out')->groupBy('order_products.product_id');
        return DataTables::of($orders)->make();
        die();

    }

    public function recentUpdate()
    {
        $notifications = DB::table('notifications')
            ->leftjoin('users', 'notifications.user_id', '=', 'users.id')
            ->select('notifications.*','users.name')
            ->limit(10)
            ->get();
        return DataTables::of($notifications)->make();
        die();
    }

    public function getNotification()
    {
        $notifications = DB::table('notifications')->leftjoin('users', 'notifications.user_id', '=', 'users.id')->select('notifications.*','users.name')->where('notifications.status','=','0')->latest('notifications.id')->first();
        $oldNotification =  $notifications;
        if(!empty($notifications->id)){
            $updateNotification = Notification::find($notifications->id);
            $updateNotification->status = 1;
            $updateNotification->update();
        }else{
            $response['status'] = 'empty';
            $oldNotification = $response;
        }
        return json_encode($oldNotification);

        die();
    }
}
