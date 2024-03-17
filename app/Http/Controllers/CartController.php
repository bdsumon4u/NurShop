<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Notification;
use App\Order;
use App\OrderProducts;
use App\Product;
use App\User;
use App\Setting;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        session_start();
        error_reporting(0);
        if(!$_SESSION['delivery']){
            $_SESSION['delivery'] = Setting::get('inside_dhaka');
        }

        return view('website.checkout');
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
     * @return Request
     */
    public function store(Request $request)
    {
        $product = Product::find($request->id);
        Cart::add($product->id,$product->productName, 1, $product->price() )->associate('App\Product');
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Cart::remove($id);
        $response['reload'] = 'true';
        if (Cart::count() > 0 ){
            $response['reload'] = 'false';
        }
        $response['status'] = 'success';
        $response['message'] = 'Successfully Add Product';
        return response()->json($response, 201);
    }

    public function mini_cart()
    {
        $response['count'] = Cart::count();
        $response['data'] = Cart::content();
        return response()->json($response, 200);
    }
    public function miniCart()
    {
        if(Cart::count() > 0){  ?>
            <a href="" class="icon icon-xs rounded-circle border" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fa fa-shopping-cart d-inline-block nav-box-icon"></i>
                <span class="badge badge-pill badge-danger notify"><?php echo Cart::count(); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right px-0" x-placement="bottom-end" style="position: absolute; transform: translate3d(-328px, 32px, 0px); top: 0px; left: 0px; will-change: transform;">
                <li>
                    <div class="dropdown-cart px-0">
                        <div class="dc-header">
                            <h4 class="text-center py-2">Cart Items</h4>
                        </div>
                        <div class="dropdown-cart-items c-scrollbar">
                            <?php foreach(Cart::content() as $item) {  ?>
                                <div class="dc-item">
                                    <div class="d-flex align-items-center">
                                        <div class="dc-image">
                                            <a href="<?php echo url('/product/'.$item->model->productSlug) ;?>">
                                                <img  src="<?php echo url('/public/product/thumbnail/'.$item->model->productImage)?>" class="img-fluid" alt="">
                                            </a>
                                        </div>
                                        <div class="dc-content">
                                            <span class="d-block dc-product-name text-capitalize strong-600 mb-1">
                                                 <a href="<?php echo url('/product/'.$item->model->productSlug) ;?>">
                                                     <?php echo $item->model->productName  ?>
                                                 </a>
                                            </span>

                                            <span class="dc-quantity">x<?php echo $item->qty ?></span>
                                            <span class="dc-price">TK <?php echo $item->model->price() ?></span>
                                        </div>
                                        <div class="dc-actions">
                                            <button onclick="removeFromCart('<?php echo $item->rowId; ?>')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="dc-item py-3">
                            <span class="subtotal-text">Subtotal</span>
                            <span class="subtotal-amount">৳ <?php echo Cart::subtotal('0','','') ?></span>
                        </div>
                        <div class="p-2 text-center dc-btn">
                            <a href="<?php echo url('/checkout'); ?>" class="link link--style-1 text-capitalize btn btn-success px-3 py-1 light-text btn-block">
                                <i class="la la-mail-forward"></i> Checkout
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        <?php }else{ ?>
            <span class="badge badge-pill badge-danger notify">0</span>
            <a href="" class="icon icon-xs rounded-circle border" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fa fa-shopping-cart d-inline-block nav-box-icon"></i>
                <span class="badge badge-pill badge-danger notify">0</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right px-0" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-328px, 32px, 0px);">

                <li>
                    <div class="dropdown-cart px-0">
                        <div class="dc-header">
                            <h4 class="text-center py-2">Empty Cart</h4>
                        </div>
                    </div>
                </li>
            </ul>
        <?php }
    }

    public function updateQuantity(Request $request)
    {
        session_start();
        if($request->quantity > 0){
            Cart::update($request->key, $request->quantity);
        }
        ?>

      <div class="order-details">

        <!-- Order Widget -->
        <div class="single-widget">
          <h2>আপনার অর্ডার</h2>
          <div class="content shopping-cart">
            <div class="table-responsive bg-white p-3">
              <table class="table border-bottom">
                <thead>
                <tr>
                  <th class="product-image"></th>
                  <th class="product-name">Product</th>
                  <th class="product-price">Price</th>
                  <th class="product-quanity">Quantity</th>
                  <th class="product-total">Total</th>
                  <th class="product-remove"></th>
                </tr>
                </thead>
                <tbody>
        <?php foreach(Cart::content() as $item) {  ?>
                <tr class="cart-item">
                  <td class="product-image">
                    <button href="#"  onclick="removeFromCart('<?php echo $item->rowId ?>')" style=" padding: 6px;; " class="btn btn-danger btn-sm">
                      <i class="fa fa-trash"></i>
                    </button>
                    <a href="#" class="mr-3">
                      <img class="lazyload" src="<?php echo url('/public/product/thumbnail/'.$item->model->productImage) ?>" style="max-width: 50px">
                    </a>
                  </td>

                  <td class="product-name" style="max-width: 100px;">
                      <?php echo $item->model->productName ?>
                  </td>

                  <td class="product-price">
                    <span class="pr-3 d-block">৳ <?php echo $item->model->price() ?></span>
                  </td>

                  <td class="qty" data-title="Qty">
                    <div class="input-group input-spinner">
                      <div class="button plus">
                        <button class="btn btn-primary btn-number" type="button" data-type="plus" data-field="quantity[<?php echo $item->id ?>]"> + </button>
                      </div>
                      <input type="text" name="quantity[<?php echo $item->id ?>]"  class="form-control input-number" placeholder="1" value="<?php echo $item->qty ?>" min="1" max="10" onchange="updateQuantity('<?php echo $item->rowId ?>', this)">
                      <div class="button minus">
                        <button class="btn btn-primary btn-number" type="button" data-type="minus"  data-field="quantity[<?php echo $item->id ?>]"> − </button>
                      </div>
                    </div>
                    <!--/ End Input Order -->
                  </td>
                  <td class="product-total" style="width: 80px;">
                    <span>৳ <?php echo $item->model->price()*$item->qty ?></span>
                  </td>
                </tr>
        <?php } ?>
                </tbody>
              </table>
            </div>
            <ul>
              <li>Sub Total<span>৳ <?php echo Cart::total('0') ?></span></li>
              <li>(+) Shipping<span>৳ <?php echo $_SESSION['delivery'] ?></span></li>
              <li class="last">Total<span>৳ <?php echo Cart::subtotal('0','','')+$_SESSION['delivery']; ?></span></li>
            </ul>
          </div>
        </div>
        <!--/ End Order Widget -->
      </div>

      <script type="text/javascript">
          cartQuantityInitialize();
      </script>
    <?php }

    public function updateDeliveryCharge(Request $request)
    {
        session_start();
        $_SESSION['delivery'] = $request->selectCourier;

    }

    public function placeOrder(Request $request)
    {

        $user = DB::table('users')->where([
            ['status', 'like', 'Active'],
            ['role_id', '=', '3']
        ])->inRandomOrder()->first();
        if (!$user) {
            $user = User::find(1);
        }
        $order = new Order();



        $order->invoiceID = $this->uniqueID();
        $order->store_id = 1;
        $order->deliveryCharge = $request->selectCourier;
        $order->orderDate = date('Y-m-d');
        $order->subTotal = Cart::subtotal('0','','')+$request->selectCourier;
        $order->user_id = $user->id;
        $order->save();
        if($order->id){
            $customer = new Customer();
            $customer->order_id = $order->id;
            $customer->customerName = $request->customerName;
            $customer->customerPhone = $request->customerPhone;
            $customer->customerAddress = $request->customerAddress;
            $customer->save();
            foreach(Cart::content() as $item) {

                $orderProducts = new OrderProducts();
                $orderProducts->order_id = $order->id;
                $orderProducts->product_id = $item->model->id;
                $orderProducts->productCode = $item->model->productCode;
                $orderProducts->productName = $item->model->productName;
                $orderProducts->quantity = $item->qty;
                $orderProducts->productPrice = $item->model->price();
                $orderProducts->save();

                $response['link'] = url('/checkout/order-received/'.$order->id);
                $response['status'] = 'success';
                $response['message'] = 'Successfully Placed Order';
                
                
                DB::table('order_user_location')->insert([
                    'order_id' => $order->id,
                    'ip_address' => request()->ip()
                ]);
                
            }
            $notification = new Notification();
            $notification->order_id = $order->id;
            $notification->notificaton = '#SD' . $order->id . ' Order Has Been Created by ' . $user->name;
            $notification->user_id = $user->id;
            $notification->save();
        } else{
            Customer::where('order_id', '=', $order->id)->delete();
            OrderProducts::where('order_id', '=', $order->id)->delete();
            Notification::where('order_id', '=', $order->id)->delete();
            Order::where('id', '=', $order->id)->delete();
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Order';
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Placed Order';
        }
        Cart::destroy();
        return response()->json($response, 201);
    }

    public function quickPlaceOrder(Request $request)
    {
        $user = DB::table('users')->where([
            ['status', 'like', 'Active'],
            ['role_id', '=', '3']
        ])->inRandomOrder()->first();
        if (!$user) {
            $user = User::find(1);
        }
        $product = Product::find($request->id);

        $order = new Order();
        $order->invoiceID = $this->uniqueID();
        $order->store_id = 1;
        $order->deliveryCharge = $request->shipping;
        $order->orderDate = date('Y-m-d');
        $order->subTotal = $product->price()+$request->shipping;
        $order->user_id = $user->id;
        $order->save();
        if($order->id){
            $customer = new Customer();
            $customer->order_id = $order->id;
            $customer->customerName = $request->name;
            $customer->customerPhone = $request->phone;
            $customer->customerAddress = $request->address;
            $customer->save();

            $orderProducts = new OrderProducts();
            $orderProducts->order_id = $order->id;
            $orderProducts->product_id = $product->id;
            $orderProducts->productCode = $product->productCode;
            $orderProducts->productName = $product->productName;
            $orderProducts->quantity = 1;
            $orderProducts->productPrice = $product->price();
            $orderProducts->save();

            $response['status'] = 'success';
            $response['total'] = $product->price()+$request->shipping;
            $response['message'] = 'Successfully Placed Order';
            $response['link'] = url('/checkout/order-received/'.$orderProducts->id);

            $notification = new Notification();
            $notification->order_id = $order->id;
            $notification->notificaton = '#SD' . $order->id . ' Order Has Been Created by ' . $user->name;
            $notification->user_id = $user->id;
            $notification->save();
            
            DB::table('order_user_location')->insert([
                'order_id' => $order->id,
                'ip_address' => request()->ip()
            ]);
            
        } else{
            Customer::where('order_id', '=', $order->id)->delete();
            OrderProducts::where('order_id', '=', $order->id)->delete();
            Notification::where('order_id', '=', $order->id)->delete();
            Order::where('id', '=', $order->id)->delete(); 
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Placed Order';
        }
        
        
        
        return response()->json($response, 201);
    }

    public function orderRecived()
    {
        return view('website.thankyou');

    }
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
}
