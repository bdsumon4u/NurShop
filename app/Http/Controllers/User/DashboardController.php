<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }
    public function getData(Request $request)
    {
        $user_id = Auth::id();
        $response['all'] = DB::table('orders')->where('orders.user_id','=',$user_id)->count();
        $response['processing'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Processing')->count();
        $response['pendingPayment'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Payment Pending')->count();
        $response['onHold'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','On Hold')->count();
        $response['canceled'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Canceled')->count();
        $response['completed'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Completed')->count();
        $response['pendingInvoiced'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Completed')->orWhere('orders.status', 'like', 'Pending Invoiced')->count();
        $response['invoiced'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Invoiced')->count();
        $response['stockOut'] =  DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Stock Out')->count();
        $response['delivered']  = DB::table('orders')->where('orders.user_id','=',$user_id)->whereIn('orders.status', ['Delivered', 'Customer Confirm'])->count();
        $response['customerOnHold'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Delivered')->count();
        $response['customerConfirm'] =  DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Customer Confirm')->count();
        $response['paid'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Paid')->count();
        $response['return'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Return')->count();
        $response['lost'] = DB::table('orders')->where('orders.user_id','=',$user_id)->where('status','like','Lost')->count();
        $response['status'] = 'success';
        return json_encode($response);

    }

}
