<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Courier;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Notification;
use App\Order;
use App\OrderProducts;
use App\Payment;
use App\PaymentType;
use App\Product;
use App\Setting;
use App\Stock;
use App\Store;
use App\User;
use App\Zone;
use App\PaymentCompelte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\DataTables;


class OrderController extends Controller
{
    // Show Orders Page
    public function index()
    {
        $status = 'all';
        return view('admin.order.index', compact('status'));
    }

    // Create Order Page
    public function create()
    {
        $unique = $this->uniqueID();
        $couriers = Courier::all();
        return view('admin.order.create', compact('unique', 'couriers'));
    }

    // Order Store
    public function store(Request $request)
    {
        $order = new Order();
        $order->invoiceID = $this->uniqueID();
        $order->store_id = $request['data']['storeID'];
        $order->subTotal = $request['data']['total'];
        $order->deliveryCharge = $request['data']['deliveryCharge'];
        $order->discountCharge = $request['data']['discountCharge'];
        $order->payment_type_id = $request['data']['paymentTypeID'];
        $order->payment_id = $request['data']['paymentID'];
        $order->paymentAmount = $request['data']['paymentAmount'];
        $order->paymentAgentNumber = $request['data']['paymentAgentNumber'];
        $order->orderDate = $request['data']['orderDate'];
        $order->courier_id = $request['data']['courierID'];
        $order->city_id = $request['data']['cityID'];
        $order->zone_id = $request['data']['zoneID'];
        $products = $request['data']['products'];
        $order->user_id = Auth::id();
        $result = $order->save();
        if ($result) {
            $customer = new Customer();
            $customer->order_id = $order->id;
            $customer->customerName = $request['data']['customerName'];
            $customer->customerPhone = $request['data']['customerPhone'];
            $customer->customerAddress = $request['data']['customerAddress'];
            $customer->save();
            foreach ($products as $product) {
                $orderProducts = new OrderProducts();
                $orderProducts->order_id = $order->id;
                $orderProducts->product_id = $product['productID'];
                $orderProducts->productCode = $product['productCode'];
                $orderProducts->productName = $product['productName'];
                $orderProducts->quantity = $product['productQuantity'];
                $orderProducts->productPrice = $product['productPrice'];
                $orderProducts->save();
            }

            $notification = new Notification();
            $notification->order_id = $order->id;
            $notification->notificaton = '#SD' . $order->id . ' Order Has Been Created by ' . Auth::user()->name;
            $notification->user_id = Auth::id();
            $notification->save();

            if ($request['data']['paymentID'] != '' && $request['data']['paymentID'] != '') {
                $paymentComplete = new PaymentCompelte();
                $paymentComplete->order_id = $order->id;
                $paymentComplete->payment_type_id = $request['data']['paymentTypeID'];
                $paymentComplete->payment_id = $request['data']['paymentID'];
                $paymentComplete->trid = $request['data']['paymentAgentNumber'];
                $paymentComplete->date = date('Y-m-d');
                $paymentComplete->userID = Auth::id();
                $paymentComplete->save();
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Order';
        } else {
            Customer::where('order_id', '=', $order->id)->delete();
            OrderProducts::where('order_id', '=', $order->id)->delete();
            Notification::where('order_id', '=', $order->id)->delete();
            Order::where('id', '=', $order->id)->delete();
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Order';
        }
        return json_encode($response);
        die();
    }

    public function show(Request $request)
    {
     
        $columns = $request->input('columns');
        $status = $request->input('status');



        $orders =  Order::with('user','courier','city','zone','products','notification') 
        ->join('customers', 'customers.order_id', '=', 'orders.id')
        ->select('orders.*','customers.customerPhone','customers.customerName','customers.customerAddress');


         
        if ($status != 'All' && $status != 'Pending Invoiced'  && $status != 'Customer On Hold' && $status != 'Delivered' && $status != 'Condition On Hold') {
            $orders = $orders->where('orders.status', 'like', $status);
        }
        if ($status == 'Pending Invoiced'  && $status != 'Delivered' && $status != 'Condition On Hold') {
            $orders = $orders->whereIn('orders.status', ['Completed', 'Pending Invoiced']);
        }
         if ($status == 'Customer On Hold') {
            $orders = $orders->whereIn('orders.status', ['Delivered', 'Customer On Hold']);
        }
        
        if ($status == 'Delivered') {
            $orders = $orders->whereIn('orders.status', ['Delivered', 'Customer Confirm','Customer On Hold','Request to Return']);
        }
        
        if ($status == 'Condition On Hold') { 
            $orders = $orders->whereIn('orders.status', ['Delivered', 'Customer On Hold']);
        }
        if ($status == 'Akhtar Express') { 
            $orders = $orders->whereIn('orders.status', ['Delivered', 'Customer On Hold']);
        }
        
        


        // 
        if ($columns[1]['search']['value']) {
            $orders = $orders->Where('orders.invoiceID', 'like', "%{$columns[1]['search']['value']}%")
                ->orWhere('orders.web_ID', 'like', "%{$columns[1]['search']['value']}%");
        }
        if ($columns[2]['search']['value']) {
            $orders = $orders->Where('customers.customerPhone', 'like', "%{$columns[2]['search']['value']}%");
        }
        if ($columns[5]['search']['value']) {
            $orders = $orders->Where('orders.courier_id', '=',$columns[5]['search']['value']);
        }
        if ($columns[6]['search']['value']) {
            if ($status == 'Delivered') {
                $orders = $orders->Where('orders.deliveryDate', 'like', "%{$columns[6]['search']['value']}%");
            } elseif ($status == 'Paid' || $status == 'Return' || $status == 'Lost') {
                $orders = $orders->Where('orders.completeDate', 'like', "%{$columns[6]['search']['value']}%");
            } else {
                $orders = $orders->Where('orders.orderDate', 'like', "%{$columns[6]['search']['value']}%");
            }
        }
        if ($columns[8]['search']['value']) {
            $orders = $orders->Where('orders.memo', 'like', "%{$columns[8]['search']['value']}%");
        }
        if ($columns[9]['search']['value']) {
            $orders = $orders->Where('orders.user_id', '=',$columns[9]['search']['value']);
        }

        //dd($orders->limit(100)->get());

         
        return DataTables::of($orders->orderBy('orders.id', 'DESC'))
             
            ->addColumn('customerInfo', function ($orders) {
                return $orders->customerName . '<br>' . $orders->customerPhone . '<br>' . $orders->customerAddress;
            })
            ->addColumn('invoice', function ($orders) {
                return $orders->invoiceID . '<br>' . $orders->web_ID;
            })
            ->editColumn('products', function ($orders) {
                $orderProducts = '';
                foreach ($orders->products as $product) {
                    $orderProducts = $orderProducts . $product->quantity . ' x ' . $product->productName . '<br>';
                }
                return rtrim($orderProducts, '<br>');
            })
            ->addColumn('action', function ($orders) {
                return "<a href='javascript:void(0);' data-id='" . $orders->id . "' class='action-icon btn-edit'> <i class='fas fa-1x fa-edit'></i></a>
                <a href='javascript:void(0);' data-id='" . $orders->id . "' class='action-icon btn-delete'> <i class='fas fa-trash-alt'></i></a>";
            })
            ->editColumn('courier', function ($orders) {
                if (isset($orders->courier->courierName)) {
                    return $orders->courier->courierName;
                } else {
                    return 'Not Selected';
                }
            })
            ->addColumn('statusButton', function ($orders) {
                if ($_REQUEST['status'] == 'Paid') {
                    return '<span class="badge bg-soft-success text-success">Paid</span>';
                } else if ($_REQUEST['status'] == 'Return') {
                    return '<span class="badge bg-soft-danger text-danger">Return</span>';
                } else if ($_REQUEST['status'] == 'Lost') {
                    return '<span class="badge bg-soft-danger text-danger">Lost</span>';
                } else if ($_REQUEST['status'] == 'Pending Invoiced') {
                    return $orders->status = $this->statusList('Pending Invoiced', $orders->id);
                } else {
                    return $orders->status = $this->statusList($orders->status, $orders->id);
                }
            })
            ->editColumn('notification', function ($orders) {
                return $orders->notification->notificaton;
            })
            ->editColumn('user', function ($orders) {
                
                if (isset($orders->user)) {
                    return $orders->user->name;
                } else {
                    return 'Not Selected';
                } 
            })
            ->only(['id','invoice','customerInfo','products','subTotal','courier','orderDate','deliveryDate','completeDate','memo','statusButton','notification','user','action'])
            ->escapeColumns([])->make();


    }
  

    public function getProductsDetails($orderID)
    {
        $products = DB::table('order_products')->select('order_products.*')->where('order_id', '=', $orderID)->get();
        $orderProducts = '';
        foreach ($products as $product) {
            $orderProducts = $orderProducts . $product->quantity . ' x ' . $product->productName . '<br>';
        }
        return rtrim($orderProducts, '<br>');
    }


    public function getNotificationDetails($orderID)
    {
        $notification = Notification::query()->where('order_id', '=', $orderID)->latest('id')->get()->first();
        if($notification){
            return $notification->notificaton;
        }else{
            return 'Order Has Been Created';
        }
        
    }


    // Edit Single Order
    public function edit($id)
    {
        $orders = DB::table('orders')
            ->select('orders.*', 'customers.customerName', 'customers.customerPhone', 'customers.customerAddress', 'couriers.courierName', 'cities.cityName', 'zones.zoneName', 'users.name', 'stores.*', 'payment_types.paymentTypeName', 'payments.paymentNumber')
            ->leftJoin('customers', 'orders.id', '=', 'customers.order_id')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->leftJoin('payment_types', 'orders.payment_type_id', '=', 'payment_types.id')
            ->leftJoin('payments', 'orders.payment_id', '=', 'payments.id')
            ->leftJoin('cities', 'orders.city_id', '=', 'cities.id')
            ->leftJoin('zones', 'orders.zone_id', '=', 'zones.id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('stores', 'orders.store_id', '=', 'stores.id')
            ->where('orders.id', '=', $id)->get()->first();
        $products = DB::table('order_products')->where('order_id', '=', $id)->get();
        $orders->products = $products;
        $orders->id = $id;
        return view('admin.order.edit')->with('order', $orders);
    }

    // Update Order
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $order->store_id = $request['data']['storeID'];
        $order->subTotal = $request['data']['total'];
        $oldAmount = $order->paymentAmount;
        $newAmount = $request['data']['paymentAmount'];
        $order->memo = $request['data']['memo'];
        $order->deliveryCharge = $request['data']['deliveryCharge'];
        $order->discountCharge = $request['data']['discountCharge'];
        $order->payment_type_id = $request['data']['paymentTypeID'];
        $order->payment_id = $request['data']['paymentID'];
        $order->paymentAmount = $request['data']['paymentAmount'];
        $order->paymentAgentNumber = $request['data']['paymentAgentNumber'];
        $order->orderDate = $request['data']['orderDate'];
        if (!empty($request['data']['deliveryDate'])) {
            $order->deliveryDate = $request['data']['deliveryDate'];
        }
        if (!empty($request['data']['completeDate'])) {
            $order->completeDate = $request['data']['completeDate'];
        }
        $order->courier_id = $request['data']['courierID'];
        $order->city_id = $request['data']['cityID'];
        $order->zone_id = $request['data']['zoneID'];
        $products = $request['data']['products'];


        $result = $order->update();
        if ($result) {
            $customer = Customer::where('order_id', '=', $id)->first();
            $customer->customerName = $request['data']['customerName'];
            $customer->customerPhone = $request['data']['customerPhone'];
            $customer->customerAddress = $request['data']['customerAddress'];
            $customer->update();
            OrderProducts::where('order_id', '=', $id)->delete();
            foreach ($products as $product) {
                $orderProducts = new OrderProducts();
                $orderProducts->order_id = $id;
                $orderProducts->product_id = $product['productID'];
                $orderProducts->productCode = $product['productCode'];
                $orderProducts->productName = $product['productName'];
                $orderProducts->quantity = $product['productQuantity'];
                $orderProducts->productPrice = $product['productPrice'];
                $orderProducts->save();
            }
            $notification = new Notification();
            $notification->order_id = $order->id;
            $notification->notificaton = Auth::user()->name . ' Update Order Details';
            $notification->user_id = Auth::id();
            $notification->save();
            $paymentComplete = PaymentCompelte::where('order_id', $order->id)->first();
            if ($paymentComplete) {
                $paymentComplete->payment_type_id = $request['data']['paymentTypeID'];
                $paymentComplete->payment_id = $request['data']['paymentID'];
                if ($newAmount != $oldAmount) {
                    $paymentComplete->amount = $request['data']['paymentAmount'];
                    $paymentComplete->date = date('Y-m-d');
                }
                $paymentComplete->trid = $request['data']['paymentAgentNumber'];
                $paymentComplete->userID = Auth::id();
                $paymentComplete->update();
            } else {
                $paymentComplete = new PaymentCompelte();
                $paymentComplete->order_id = $order->id;
                $paymentComplete->payment_type_id = $request['data']['paymentTypeID'];
                $paymentComplete->payment_id = $request['data']['paymentID'];
                $paymentComplete->amount = $request['data']['paymentAmount'];
                $paymentComplete->trid = $request['data']['paymentAgentNumber'];
                $paymentComplete->date = date('Y-m-d');
                $paymentComplete->userID = Auth::id();
                $paymentComplete->save();
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Order';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update Order';
        }
        return json_encode($response);
    }

    // Delete Single Order
    public function destroy($id)
    {
        $result = Order::find($id)->delete();
        if ($result) {
            Customer::query()->where('order_id', '=', $id)->delete();
            OrderProducts::query()->where('order_id', '=', $id)->delete();
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Order';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Order';
        }
        return json_encode($response);
    }

    // Show Order By status
    public function ordersByStatus($status)
    {
        $users = DB::table('users')->where([
            ['status', 'like', 'Active'],
            ['role_id', '=', '3']
        ])->inRandomOrder()->get();
        if ($status == 'Pending Invoiced' || $status == 'Invoiced' || $status == 'Stock Out') {
            return view('admin.order.invoiced', compact('status', 'users'));
        }
        if ($status == 'Delivered' || $status == 'Customer Confirm' || $status == 'Customer On Hold' || $status == 'Request to Return' || $status == 'Paid' || $status == 'Return' || $status == 'Lost') {
            return view('admin.order.delivered', compact('status', 'users'));
        } else {
            return view('admin.order.index', compact('status', 'users'));
        }

    }
    // get Users
    public function users(Request $request)
    {
        if (isset($request['q'])) {
            $$users = User::query()->where([
                ['name', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active']
            ])->get();
        } else {
            $users = User::query()->where('status', 'like', 'Active')->get();
        }
        $user = array();
        foreach ($users as $item) {
            $user[] = array(
                "id" => $item['id'],
                "text" => $item['name']
            );
        }
        return json_encode($user);
        die();
    }

    // Get Products
    public function product(Request $request)
    {
        if (isset($request['q'])) {
            $products = Product::query()->where('productName', 'like', '%' . $request['q'] . '%')->get();
        } else {
            $products = Product::all();
        }
        $product = array();
        foreach ($products as $item) {
            if (App::environment('local')) {
                $item['productImage'] = url('/product/' . $item['productImage']);
            } else {
                $item['productImage'] = url('/public/product/' . $item['productImage']);
            }

            $product[] = array(
                "id" => $item['id'],
                "text" => $item->productName,
                "image" => $item->productImage,
                "productCode" => $item->productCode,
                "productPrice" => $item->price()
            );
        }
        $data['data'] = $product;
        return json_encode($data);
        die();
    }

    // get Stores
    public function stores(Request $request)
    {
        if (isset($request['q'])) {
            $stores = Store::query()->where([
                ['storeName', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active']
            ])->get();
        } else {
            $stores = Store::query()->where('status', 'like', 'Active')->get();
        }
        $store = array();
        foreach ($stores as $item) {
            $store[] = array(
                "id" => $item['id'],
                "text" => $item['storeName']
            );
        }
        return json_encode($store);
        die();
    }

    // Get Payment Type
    public function paymenttype(Request $request)
    {
        if (isset($request['q'])) {
            $paymentTypes = PaymentType::query()->where([
                ['paymentTypeName', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active']
            ])->get();
        } else {
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

    // Get Payment Number
    public function paymentnumber(Request $request)
    {
        if (isset($request['q']) && $request['paymentTypeID']) {
            $payments = Payment::query()->where([
                ['paymentNumber', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active'],
                ['payment_type_id', '=', $request['paymentTypeID']]
            ])->get();
        } else {
            $payments = Payment::query()->where([
                ['status', 'like', 'Active'],
                ['payment_type_id', '=', $request['paymentTypeID']]
            ])->get();
        }
        $payment = array();
        foreach ($payments as $item) {
            $payment[] = array(
                "id" => $item['id'],
                "text" => $item['paymentNumber']
            );
        }
        return json_encode($payment);
    }

    // Get Courier
    public function courier(Request $request)
    {
        if (isset($request['q'])) {
            $couriers = Courier::query()->where([
                ['courierName', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active']
            ])->get();
        } else {
            $couriers = Courier::query()->where('status', 'like', 'Active')->get();
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

    // Get City
    public function city(Request $request)
    {
        if (isset($request['q']) && $request['courierID']) {
            $cites = City::query()->where([
                ['cityName', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active'],
                ['courier_id', '=', $request['courierID']]
            ])->get();
        } else {
            $cites = City::query()->where([
                ['status', 'like', 'Active'],
                ['courier_id', '=', $request['courierID']]
            ])->get();
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

    // Get Zone
    public function zone(Request $request)
    {
        if (isset($request['q'])) {
            $zones = Zone::query()->where([
                ['zoneName', 'like', '%' . $request['q'] . '%'],
                ['courier_id', '=', $request['courierID']],
                ['status', 'like', 'Active'],
                ['city_id', '=', $request['cityID']]
            ])->get();
        } else {
            $zones = Zone::query()->where([
                ['courier_id', '=', $request['courierID']],
                ['city_id', '=', $request['cityID']],
                ['status', 'like', 'Active']
            ])->get();
        }
        $zone = array();
        foreach ($zones as $item) {
            $zone[] = array(
                "id" => $item['id'],
                "text" => $item['zoneName']
            );
        }
        return json_encode($zone);
    }

    // All Status List
    public function statusList($status, $id)
    {
        $allStatus = array(
            'order' => array(
                "Processing" => array(
                    "name" => "Processing",
                    "icon" => "fe-tag",
                    "color" => "bg-primary"
                ),
                "On Hold" => array(
                    "name" => "On Hold",
                    "icon" => "far fa-stop-circle",
                    "color" => "bg-warning"
                ),
                "Payment Pending" => array(
                    "name" => "Payment Pending",
                    "icon" => "fe-tag",
                    "color" => "bg-info"
                ),
                "Canceled" => array(
                    "name" => "Canceled",
                    "icon" => "fe-trash-2",
                    "color" => "bg-danger"
                ),
                "Completed" => array(
                    "name" => "Completed",
                    "icon" => "fe-check-circle",
                    "color" => "bg-success"
                )
            ),
            'invoice' => array(
                "Pending Invoiced" => array(
                    "name" => "Pending Invoiced",
                    "color" => "bg-primary"
                ),
                "Invoiced" => array(
                    "name" => "Invoiced",
                    "color" => "bg-warning"
                ),
                "Stock Out" => array(
                    "name" => "Stock Out",
                    "color" => "bg-info"
                ),
                "Canceled" => array(
                    "name" => "Canceled",
                    "color" => "bg-info"
                ),
                "Delivered" => array(
                    "name" => "Delivered",
                    "color" => "bg-info"
                )
            ),
            'delivered' => array(
                "Delivered" => array(
                    "name" => "Delivered",
                    "color" => "bg-primary"
                ),
                "Customer On Hold" => array(
                    "name" => "Customer On Hold",
                    "color" => "bg-warning"
                ),
                "Customer Confirm" => array(
                    "name" => "Customer Confirm",
                    "color" => "bg-warning"
                ),
                 "Request to Return" => array(
                    "name" => "Request to Return",
                    "color" => "bg-warning"
                ),
                "Paid" => array(
                    "name" => "Paid",
                    "color" => "bg-info"
                ),
                "Return" => array(
                    "name" => "Return",
                    "color" => "bg-danger"
                ),
                "Lost" => array(
                    "name" => "Lost",
                    "color" => "bg-danger"
                )
            )
        );

        $temp = 'order';
        foreach ($allStatus as $key => $value) {
            foreach ($value as $kes => $val) {
                if ($kes == $status) {
                    $temp = $key;
                }
            }
        }
        $args = $allStatus[$temp];
        $html = '';
        foreach ($args as $value) {
            if ($args[$status]['name'] != $value['name']) {
                $html = $html . "<a class='dropdown-item btn-status' data-id='" . $id . "' data-status='" . $value['name'] . "' href='#'>" . $value['name'] . "</a>";
            }
        }
        $response = "<div class='btn-group dropdown'>
            <a href='javascript: void(0);'  class='table-action-btn dropdown-toggle arrow-none btn " . $args[$status]['color'] . " btn-xs' data-toggle='dropdown' aria-expanded='false'>" . $args[$status]['name'] . " <i class='mdi mdi-chevron-down'></i></a>
            <div class='dropdown-menu dropdown-menu-right'>
            " . $html . "
            </div>
        </div>";

        return $response;
    }

    //
    public function view(Request $request)
    {

    }

    // Create Invoice ID
    public function uniqueID()
    {
        $lastOrder = Order::latest('id')->first();
        if ($lastOrder) {
            $orderID = $lastOrder->id + 1;
        } else {
            $orderID = 1;
        }

        return 'SD' . $orderID;
    }

    // Order Sync
    public function orderSync(Request $request)
    {
        $stores = Store::query()->where('status', 'like', 'Active')->get();
        // dd($stores);

        $orderCount = 0;
        foreach ($stores as $store) {

            $syncOrders = json_decode($this->getOrders($store->storeUrl));

            foreach ($syncOrders as $syncOrder) {

                $orderExist = Order::query()->where([
                    ['web_ID', '=', $syncOrder->wp_id],
                    ['store_id', '=', $store->id]
                ])->get()->first();

                if (!$orderExist) {
                    $user = DB::table('users')->where([
                        ['status', 'like', 'Active'],
                        ['role_id', '=', '3']
                    ])->inRandomOrder()->first();

                    if (!$user) {
                        $user_id = 1;
                    } else {
                        $user_id = $user->id;
                    }
                    
                    
                    $order = new Order();
                    $order->invoiceID = $this->uniqueID();
                    $order->web_ID = $syncOrder->wp_id;
                    $order->subTotal = $syncOrder->total;
                    $order->orderDate = date('Y-m-d');
                    $order->user_id = $user_id;
                    $order->store_id = $store->id;

                    if(isset($syncOrder->deliveryCharge)){
                        $order->deliveryCharge = $syncOrder->deliveryCharge;
                    }else{
                        $order->deliveryCharge = 100;
                    }
                    
                    $result = $order->save();
                    $products = $syncOrder->products;
                    if ($result) {
                        $customer = new Customer();
                        $customer->order_id = $order->id;
                        $customer->customerName = $syncOrder->customer->first_name;
                        $customer->customerPhone = $syncOrder->customer->phone;
                        $customer->customerAddress = $syncOrder->customer->address_1;
                        $customer->save();
                        foreach ($products as $product) {
                            $orderProducts = new OrderProducts();
                            $productExist = Product::query()->where('productCode', 'like', $product->sku)->get()->first();
                            if ($productExist) {
                                $orderProducts->order_id = $order->id;
                                $orderProducts->product_id = $productExist->id;
                                $orderProducts->productCode = $product->sku;
                                $orderProducts->productName = $product->product_name;
                                $orderProducts->quantity = $product->quantity;
                                $orderProducts->productPrice = $product->price;
                                $orderProducts->save();
                            } else {
                                $this->productSync();
                                $productExist = Product::query()->where('productCode', 'like', $product->sku)->get()->first();
                                $orderProducts->order_id = $order->id;
                                $orderProducts->product_id = $productExist->id;
                                $orderProducts->productCode = $product->sku;
                                $orderProducts->productName = $product->product_name;
                                $orderProducts->quantity = $product->quantity;
                                $orderProducts->productPrice = $product->price;
                                $orderProducts->save();
                            }
                        }
                        $notification = new Notification();
                        $notification->order_id = $order->id;
                        $notification->notificaton = 'Order Has Been Created';
                        $notification->user_id = $user_id;
                        $notification->save();
                    }
                    $orderCount++;
                } else {
                    //echo 'Exist';
                }
            }

        }

        if ($orderCount > 0) {
            $response['status'] = 'success';
            $response['orders'] = $orderCount;
        } else {
            $response['status'] = 'failed';
            $response['orders'] = $orderCount;
        }
        return json_encode($response);
    }

    // Get Orders from website
    public function getOrders($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/wp-json/inventory/v1/order/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        return curl_exec($curl);
    }

    // Delete All Orders
    public function deleteAll(Request $request)
    {
        $ids = $request['ids'];
        if ($ids) {
            foreach ($ids as $id) {
                if (Auth::id() == 1) {
                    // Order::query()->truncate();
                    // Customer::query()->truncate();
                    // OrderProducts::query()->truncate();
                    // Notification::query()->truncate();
                } else {
                    $result = Order::find($id)->delete();
                    if ($result) {
                        Customer::query()->where('order_id', '=', $id)->delete();
                        OrderProducts::query()->where('order_id', '=', $id)->delete();
                        Notification::query()->where('order_id', '=', $id)->delete();
                    }
                }
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Order';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Order';
        }
        return json_encode($response);
    }

    // Assign Order to a user
    public function assign(Request $request)
    {
        $user_id = $request['user_id'];
        $ids = $request['ids'];
        if ($ids) {
            foreach ($ids as $id) {
                $order = Order::find($id);
                $order->user_id = $user_id;
                $order->save();
                $notification = new Notification();
                $user = User::find($user_id);
                $notification->order_id = $id;
                $notification->notificaton = Auth::user()->name . ' Successfully Assign #SD' . $id . ' Order to ' . $user->name;
                $notification->user_id = Auth::id();
                $notification->save();
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Assign User to this Order';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Assign User to this Order';
        }
        return json_encode($response);
    }

    // Change Single order Status
      public function status(Request $request)
    {
        $id = $request['id'];
        $status = $request['status'];
        $order = Order::find($id);
        $response = [];

        if($status == 'Delivered'){ 
            $order->deliveryDate = date('Y-m-d');
            $stockAvailable = 0;
            $orderProducts = OrderProducts::query()->where('order_id', '=', $order->id)->get();
            foreach ($orderProducts as $orderProduct) {
                $stock = Stock::query()->where('product_id', '=', $orderProduct->product_id)->first();
                if($stock->stock > 0){
                    $stockAvailable++;
                }
            }
            if($stockAvailable == count($orderProducts)){
                foreach ($orderProducts as $orderProduct) {
                    $stock = Stock::query()->where('product_id', '=', $orderProduct->product_id)->first();
                    $stock->stock = $stock->stock - $orderProduct->quantity;
                    $stock->save();
                }
                $order->status = $status;
                $result = $order->save();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Update Status to ' . $request['status'];
            }else{
                $response['status'] = 'failed';
                $response['message'] = 'Not in Stock';
            } 
            
        }else if ($status == 'Return') {
            
            $order->completeDate = date('Y-m-d');
            $orderProducts = OrderProducts::query()->where('order_id', '=', $order->id)->get();
            foreach ($orderProducts as $orderProduct) {
                $stock = Stock::query()->where('product_id', '=', $orderProduct->product_id)->first();
                $stock->stock = $stock->stock + $orderProduct->quantity;
                $stock->save();
            }
            $order->status = $status;
            $result = $order->save();
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Status to ' . $request['status'];
            
        }else{
        
            if ($request['status'] == 'Completed') {
                
                $order->orderDate = date('Y-m-d');
                
            } if ($request['status'] == 'Paid') {
                
                $order->completeDate = date('Y-m-d');
                
            }
            if ($order->courier_id || $status == 'Canceled' || $status == 'On Hold' || $status == 'Payment Pending') {
                $order->status = $status;
                $result = $order->save();
                if ($result) {
                    $response['status'] = 'success';
                    $response['message'] = 'Successfully Update Status to ' . $request['status'];
                    $notification = new Notification();
                    $notification->order_id = $id;
                    $notification->notificaton = Auth::user()->name . ' Successfully Update #DN' . $id . ' Order status to ' . $status;
                    $notification->user_id = Auth::id();
                    $notification->save();
                } else {
                    $response['status'] = 'failed';
                    $response['message'] = 'Unsuccessful to update Status ' . $request['status'];
                }
            } else {
                $response['status'] = 'failed';
                $response['message'] = 'Please Update order courier and try again !';
            }
        
        }

        return json_encode($response);
    }

    // Change Multiple Order Status
    public function changeStatusByCheckbox(Request $request)
    {

        $status = $request['status'];
        $ids = $request['ids'];
        if ($ids) {
            foreach ($ids as $id) {
                
                
                $order = Order::find($id); 


                if($status == 'Delivered'){ 
                    $order->deliveryDate = date('Y-m-d');
                    $stockAvailable = 0;
                    $orderProducts = OrderProducts::query()->where('order_id', '=', $order->id)->get();
                    foreach ($orderProducts as $orderProduct) {
                        $stock = Stock::query()->where('product_id', '=', $orderProduct->product_id)->first();
                        if($stock->stock > 0){
                            $stockAvailable++;
                        }
                    }
                    if($stockAvailable == count($orderProducts)){
                        
                        foreach ($orderProducts as $orderProduct) {
                            $stock = Stock::query()->where('product_id', '=', $orderProduct->product_id)->first();
                            $stock->stock = $stock->stock - $orderProduct->quantity;
                            $stock->save();
                        }
                        $order->status = $status;
                        $result = $order->save();
                        $response['status'] = 'success';
                        $response['message'] = 'Successfully Update Status to ' . $request['status'];
                        
                    }else{
                        
                        $response['status'] = 'failed';
                        $response['message'] = 'Not in Stock';
                        
                    } 
                    
                }else if ($status == 'Return') {
                    
                    $order->completeDate = date('Y-m-d');
                    $orderProducts = OrderProducts::query()->where('order_id', '=', $order->id)->get();
                    foreach ($orderProducts as $orderProduct) {
                        $stock = Stock::query()->where('product_id', '=', $orderProduct->product_id)->first();
                        $stock->stock = $stock->stock + $orderProduct->quantity;
                        $stock->save();
                    }
                    $order->status = $status;
                    $result = $order->save();
                    $response['status'] = 'success';
                    $response['message'] = 'Successfully Update Status to ' . $request['status'];
                    
                }else{
                
                    if ($request['status'] == 'Completed') {
                        
                        $order->orderDate = date('Y-m-d');
                        
                    } if ($request['status'] == 'Paid') {
                        
                        $order->completeDate = date('Y-m-d');
                        
                    }
                    if ($order->courier_id || $status == 'Canceled' || $status == 'On Hold' || $status == 'Payment Pending') {
                        $order->status = $status;
                        $result = $order->save();
                        if ($result) {
                            $response['status'] = 'success';
                            $response['message'] = 'Successfully Update Status to ' . $request['status'];
                            $notification = new Notification();
                            $notification->order_id = $id;
                            $notification->notificaton = Auth::user()->name . ' Successfully Update #DN' . $id . ' Order status to ' . $status;
                            $notification->user_id = Auth::id();
                            $notification->save();
                        } else {
                            $response['status'] = 'failed';
                            $response['message'] = 'Unsuccessful to update Status ' . $request['status'];
                        }
                    } else {
                        $response['status'] = 'failed';
                        $response['message'] = 'Please Update order courier and try again !';
                    }
                
                } 
                
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Status to ' . $request['status'];
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Please Update order and try again !';
        }
        return json_encode($response);
    }

    //
    public function pendingInvoiced()
    {
        $status = 'all';
        return view('admin.order.index', compact('status'));
    }

    // Product Sync if Not exist
    public function productSync()
    {
        $stores = Store::query()->where('status', 'like', 'Active')->get();
        $orderCount = 0;
        foreach ($stores as $store) {
            $syncProducts = json_decode($this->getProducts($store->storeUrl));
            foreach ($syncProducts as $syncProduct) {
                $LocalProduct = Product::where('productCode', 'like', $syncProduct->sku)->get()->first();
                if (!$LocalProduct && $syncProduct->price) {
                    $image = $syncProduct->image;
                    $imageName = uniqid() . '.jpg';
                    $img = public_path('product/') . $imageName;
                    file_put_contents($img, $this->curl_get_file_contents($image));
                    $newProduct = new Product();
                    $newProduct->productCode = $syncProduct->sku;
                    $newProduct->productName = $syncProduct->name;
                    $newProduct->productPrice = $syncProduct->price;
                    $newProduct->productImage = $imageName;
                    $newProduct->save();
                    $orderCount++;
                }
            }

        }
        if ($orderCount > 0) {
            $response['status'] = 'success';
            $response['products'] = $orderCount;
        } else {
            $response['status'] = 'failed';
            $response['products'] = $orderCount;
        }
        return json_encode($response);
    }

    // Get Products From website
    public function getProducts($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "/wp-json/inventory/v1/products/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        return curl_exec($curl);
    }

    // Get Note of order
    public function getNotes(Request $request)
    {
        $order_id = $request['id'];
        $notification = Notification::query()->where('order_id', '=', $order_id)->latest()->get();
        $notification['data'] = $notification->map(function ($notification) {
            $user = DB::table('users')->select('users.name')->where('id', '=', $notification->user_id)->get()->first();
            $notification->name = $user->name;
            $notification->date = $this->time_ago_in_php($notification->created_at);
            return $notification;
        });
        return json_encode($notification);

    }

    // Update Note of Order
    public function updateNotes(Request $request)
    {
        $id = $request['id'];
        $note = $request['note'];
        $notification = new Notification();
        $notification->order_id = $id;
        $notification->notificaton = $note;
        $notification->user_id = Auth::id();
        $request = $notification->save();

        if ($request) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully to Update Order note';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update Order note';
        }
        return json_encode($response);
        die();

    }

    // Change time to facebook Style
    public function time_ago_in_php($timestamp)
    {

        date_default_timezone_set("Asia/Dhaka");
        $time_ago = strtotime($timestamp);
        $current_time = time();
        $time_difference = $current_time - $time_ago;
        $seconds = $time_difference;

        $minutes = round($seconds / 60); // value 60 is seconds
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;
        $weeks = round($seconds / 604800); // 7*24*60*60;
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

        if ($seconds <= 60) {

            return "Just Now";
        } else if ($minutes <= 60) {

            if ($minutes == 1) {

                return "one minute ago";
            } else {

                return "$minutes minutes ago";
            }
        } else if ($hours <= 24) {

            if ($hours == 1) {

                return "an hour ago";
            } else {

                return "$hours hrs ago";
            }
        } else if ($days <= 7) {

            if ($days == 1) {

                return "yesterday";
            } else {

                return "$days days ago";
            }
        } else if ($weeks <= 4.3) {

            if ($weeks == 1) {

                return "a week ago";
            } else {

                return "$weeks weeks ago";
            }
        } else if ($months <= 12) {

            if ($months == 1) {

                return "a month ago";
            } else {

                return "$months months ago";
            }
        } else {

            if ($years == 1) {

                return "one year ago";
            } else {

                return "$years years ago";
            }
        }
    }

    // Get Old Orders
    public function oldOrders(Request $request)
    {
        $order_id = $request['id'];
        $customer = Customer::query()->where('order_id', '=', $order_id)->get()->first();
        $orders = DB::table('orders')
            ->select('orders.*', 'customers.*')
            ->leftJoin('customers', 'orders.id', '=', 'customers.order_id')
            ->where([
                ['customers.order_id', '!=', $order_id],
                ['customers.customerPhone', 'like', $customer->customerPhone]
            ])->get();
        $order['data'] = $orders->map(function ($order) {
            $products = DB::table('order_products')->select('order_products.*')->where('order_id', '=', $order->id)->get();
            $orderProducts = '';
            foreach ($products as $product) {
                $orderProducts = $orderProducts . $product->quantity . ' x ' . $product->productName . '<br>';
            }
            $order->products = rtrim($orderProducts, '<br>');
            return $order;
        });
        return json_encode($order);

//        return $orders;
    }

    // Get Status Wise order Count
    public function countOrders()
    {
        $response['all'] = DB::table('orders')->count();
        $response['processing'] = DB::table('orders')->where('status', 'like', 'Processing')->count();
        $response['pendingPayment'] = DB::table('orders')->where('status', 'like', 'Payment Pending')->count();
        $response['onHold'] = DB::table('orders')->where('status', 'like', 'On Hold')->count();
        $response['canceled'] = DB::table('orders')->where('status', 'like', 'Canceled')->count();
        $response['completed'] = DB::table('orders')->where('status', 'like', 'Completed')->count();
        $response['pendingInvoiced'] = DB::table('orders')->where('status', 'like', 'Completed')->orWhere('orders.status', 'like', 'Pending Invoiced')->count();
        $response['invoiced'] = DB::table('orders')->where('status', 'like', 'Invoiced')->count();
        $response['stockOut'] = DB::table('orders')->where('status', 'like', 'Stock Out')->count();
        $response['delivered'] = DB::table('orders')->whereIn('orders.status', ['Delivered', 'Customer Confirm','Customer On Hold','Request to Return'])->count();
        $response['customerOnHold'] = DB::table('orders')->whereIn('orders.status', ['Delivered', 'Customer On Hold'])->count();
        $response['customerConfirm'] = DB::table('orders')->where('status', 'like', 'Customer Confirm')->count();
        $response['requestToReturn'] = DB::table('orders')->where('status', 'like', 'Request to Return')->count();
        $response['paid'] = DB::table('orders')->where('status', 'like', 'Paid')->count();
        $response['return'] = DB::table('orders')->where('status', 'like', 'Return')->count();
        $response['lost'] = DB::table('orders')->where('status', 'like', 'Lost')->count();
        $response['status'] = 'success';
        return json_encode($response);
    }

    // Invoice Display
    public function storeInvoice(Request $request)
    {
        $ids = serialize($request['ids']);
        $invoice = new Invoice();
        $invoice->order_id = $ids;
        $result = $invoice->save();
        if ($result) {
            $response['status'] = 'success';
            $response['link'] = url('admin/order/invoice/') . '/' . $invoice->id;
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Order';
        }
        return json_encode($response);
        die();
    }

    public function viewInvoice($id)
    {
        $invoice = Invoice::find($id);
        return view('admin.order.print', compact('invoice'));

    }

    public function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);
        if ($contents) return $contents;
        else return FALSE;
    }

    public function getSotreUrl($id)
    {
        $store = Store::find($id);
        return $store->storeUrl;
    }

    public function sendNumber(Request $request)
    {
        $settings = Setting::get('sms_content');
        $url = "http://66.45.237.70/api.php";
        $customerPhone = $request['customerPhone'];
        $invoiceID = $request['invoiceID'];
        $paymentTypeID = $request['paymentTypeID'];
        $orderID = $request['orderID'];
        $storeURL = $this->getSotreUrl($request['storeID']);
        $paymentID = $request['paymentID'];
        $replaceTag = ["{ID}", "{method}", "{number}", "{site_url}", "{invoiceID}"];
        $tag = [$orderID, $paymentTypeID, $paymentID, $storeURL, $invoiceID];
        $text = str_replace($replaceTag, $tag, $settings);

        $data = array(
            'username' =>  Setting::get('sms_username'),
            'password' =>  Setting::get('sms_password'),
            'number' => "$customerPhone",
            'message' => "$text"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("|", $smsresult);
        $sendstatus = $p[0];
        if ($sendstatus == '1101') {
            $notification = new Notification();
            $notification->order_id = $orderID;
            $notification->notificaton = Auth::user()->name . ' Send Sms for ' . $paymentTypeID . ' payment on ' . $paymentID . ' For ' . $orderID . ' Order';
            $notification->user_id = Auth::id();
            $notification->save();
            $settings = Setting::find(1);
            $settings->value = $settings->value + 1;
            $settings->update();
            $response['status'] = 'success';
            $response['message'] = 'Successfully Send SMS';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Send SMS';
        }
        return json_encode($response);
        die();
    }

    public function sendMemoNumbers(Request $request)
    {

    }


    public function memoUpdate(Request $request)
    {
        $order = Order::find($request->id);
        if ($order->status != 'Paid' && $order->status != 'Return' && $order->status != 'Lost') {
            $order->memo = $request->memo;
            $result = $order->update();
            if ($result) {
                $notification = new Notification();
                $notification->order_id = $request->id;
                $notification->notificaton = Auth::user()->name . ' Update #SD' . $request->id . ' Order Memo To ' . $request->memo;
                $notification->user_id = Auth::id();
                $notification->save();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Updated Order Memo';
            } else {
                $response['status'] = 'failed';
                $response['message'] = 'Unsuccessful to Updated Order Memo';
            }
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Updated Order Memo';
        }
        return json_encode($response);
        die();
    }
    
     public function csvExportRedxStore(Request $request)
    {
        $ids = serialize($request['ids']);
        $invoice = new Invoice();
        $invoice->order_id = $ids;
        $result = $invoice->save();
        if ($result) {
            $response['status'] = 'success';
            $response['link'] = url('admin/order/csvExportRedx/') . '/' . $invoice->id;
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Order';
        }
        return json_encode($response);
        die();
    }
 

 public function csvExportRedx(Request $response)
    {

        header('Content-Encoding: UTF-8');
        header('Content-type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename=redx-'.date('Y-m-d').'.xls');
        echo "\xEF\xBB\xBF";

        $headers = array(
            "Content-type" => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=file.xls",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $invoice = Invoice::find($response->id);
        $orderIDs = unserialize($invoice->order_id);

        $orders = DB::table('orders')
            ->leftjoin('customers', 'orders.id', '=', 'customers.order_id')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->leftJoin('cities', 'orders.city_id', '=', 'cities.id')
            ->leftJoin('zones', 'orders.zone_id', '=', 'zones.id')
            ->select('orders.*', 'customers.customerName', 'customers.customerPhone', 'customers.customerAddress','cities.belongsTo as district','cities.cityName as division','zones.zoneName as area','zones.belongsToID as areaID')->whereIn('orders.id',$orderIDs)->latest('id')->get();
    $columns = array('Invoice', 'Customer Name', 'Contact No.', 'Customer Address','District','Area','Area ID','Division','Price','Weight(g)','Instruction', 'Product Selling Price','Seller Name','Seller Phone');
//         dd($orders);
        $callback = function() use ($orders, $columns)
        {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach($orders as $order) {
                fputcsv($file, array($order->invoiceID, $order->customerName,ltrim(str_replace(' ','',str_replace('880','',str_replace('-','',$order->customerPhone)))), $order->customerAddress,$order->district ,$order->area,$order->areaID,$order->division, $order->subTotal,'500', '', $order->subTotal,'',''));
            }
            mb_convert_encoding($file, 'UTF-16LE', 'UTF-8');

            fclose($file);
        };

        return response()->stream($callback, 200);
    }


}
