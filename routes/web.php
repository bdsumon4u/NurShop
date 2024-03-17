<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'WebsiteController@index')->name('home');
// Route::get('/product/{id}', 'WebsiteController@product')->name('product');
Route::get('/product/{slug}/{id}', 'WebsiteController@slugProduct')->name('product');
Route::get('/category/{slug}', 'WebsiteController@category')->name('category');
Route::get('/page/{slug}', 'WebsiteController@page')->name('page');
Route::get('/shop', 'WebsiteController@shop')->name('shop');

Route::get('/getProducts/', 'WebsiteController@loadProducts')->name('loadProducts');

Route::resource('/checkout','CartController');
Route::get('/mini_cart','CartController@mini_cart')->name('mini_cart');
Route::get('/miniCart','CartController@miniCart')->name('miniCart');
Route::get('/updateQuantity','CartController@updateQuantity')->name('updateQuantity');
Route::get('/updateDeliveryCharge','CartController@updateDeliveryCharge')->name('updateDeliveryCharge');
Route::post('/placeOrder','CartController@placeOrder')->name('placeOrder');
Route::post('/quickPlaceOrder','CartController@quickPlaceOrder')->name('quickPlaceOrder');
Route::get('/checkout/order-received/{id}','CartController@orderRecived')->name('placeOrder');


Route::get('/location', function () {
    
    return exec('getmac');

 
    // return request()->ip();
    
});


//Route::get('/', function () {
//    return redirect('login');
//});

//Route::get('pathao', 'PathaoController@pathao')->name('pathao');
//Route::get('deliveryTiger', 'DeliveryTigerController@deliveryTiger')->name('deliveryTiger');
//Route::get('redx', 'PathaoController@redx')->name('redx');

Route::get('/user', function (){
    return redirect('user/dashboard');
});
Route::get('/admin', function (){
    return redirect('admin/dashboard');
});

Route::get('pathao/booking', 'PathaoController@booking')->name('pathao.booking');

Auth::routes(['except' => 'register']);

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('dashboard/getData', 'DashboardController@getData')->name('getData');
    Route::get('dashboard/stockOutProduct', 'DashboardController@stockOutProduct')->name('stockOutProduct');
    Route::get('dashboard/recentUpdate', 'DashboardController@recentUpdate')->name('recentUpdate');
    Route::get('dashboard/getNotification', 'DashboardController@getNotification')->name('getNotification');


    //

    // Products
    Route::get('product/oldProductSync', 'ProductController@oldProductSync')->name('oldProductSync');
    Route::get('product/productSync', 'ProductController@productSync')->name('productSync');
    Route::post('product/image', 'ProductController@image')->name('image');
    Route::post('product/status', 'ProductController@status')->name('status');
    Route::post('product/category','ProductController@category');
    Route::post('product/relatedProducts','ProductController@relatedProducts');
    Route::get('product/delete','ProductController@delete')->name('delete');
    Route::resource('product', 'ProductController');

    // Media
    Route::get('media/get','MediaController@get')->name('get');
    Route::get('media/delete','MediaController@delete')->name('delete');
    Route::get('media/iframeget','MediaController@iframeget')->name('iframeget');
    Route::get('media/iframe','MediaController@iframe')->name('iframe');
    Route::resource('media','MediaController');

    // Category
    Route::post('category/status','CategoryController@status');
    Route::get('category/get','CategoryController@get');
    Route::get('category/delete','CategoryController@delete')->name('delete');
    Route::resource('category','CategoryController');


    // Store
    Route::post('store/status', 'StoreController@status')->name('status');
    Route::resource('store', 'StoreController');

    // Supplier

    Route::post('supplier/status', 'SupplierController@status')->name('status');
    Route::resource('supplier', 'SupplierController');

    // Purchase
    Route::get('purchase/supplier', 'PurchaseController@supplier')->name('supplier');
    Route::get('purchase/product', 'PurchaseController@product')->name('product');
    Route::resource('purchase', 'PurchaseController');

    // Product Stock
    Route::resource('stock', 'StockController');

    // Notification
    Route::resource('notification', 'NotificationController');


    // Order



    Route::get('order/deleteAll', 'OrderController@deleteAll')->name('deleteAll');
    Route::get('order/status', 'OrderController@status')->name('status');
    Route::get('order/orderSync', 'OrderController@orderSync')->name('orderSync');
    Route::get('order/view', 'OrderController@view')->name('view');

    Route::get('order/status/{status}', 'OrderController@ordersByStatus')->name('ordersByStatus');

    Route::get('order/csvExportRedx/{id}', 'OrderController@csvExportRedx')->name('csvExportRedx');
    Route::get('order/csvExportRedxStore', 'OrderController@csvExportRedxStore')->name('csvExportRedxStore');

    Route::get('order/assign', 'OrderController@assign')->name('assign');
    Route::get('order/changeStatusByCheckbox', 'OrderController@changeStatusByCheckbox')->name('changeStatusByCheckbox');
    Route::get('order/getNotes', 'OrderController@getNotes')->name('getNotes');
    Route::get('order/updateNotes', 'OrderController@updateNotes')->name('updateNotes');
    Route::get('order/oldOrders', 'OrderController@oldOrders')->name('oldOrders');


    Route::get('order/product', 'OrderController@product')->name('product');
    Route::get('order/stores', 'OrderController@stores')->name('stores');
    Route::get('order/users', 'OrderController@users')->name('users');
    Route::get('order/courier', 'OrderController@courier')->name('courier');
    Route::get('order/city', 'OrderController@city')->name('city');
    Route::get('order/zone', 'OrderController@zone')->name('zone');
    Route::get('order/paymenttype', 'OrderController@paymenttype')->name('paymenttype');
    Route::get('order/paymentnumber', 'OrderController@paymentnumber')->name('paymentnumber');


    Route::get('order/countOrders', 'OrderController@countOrders')->name('countOrders');
    Route::get('order/invoice', 'OrderController@invoice')->name('invoice');
    Route::get('order/storeInvoice', 'OrderController@storeInvoice')->name('storeInvoice');
    Route::get('order/invoice/{id}', 'OrderController@viewInvoice')->name('viewInvoice');
    Route::get('order/memoUpdate', 'OrderController@memoUpdate')->name('memoUpdate');



    // Send Sms
    Route::get('order/sendNumber', 'OrderController@sendNumber')->name('sendNumber');

    Route::resource('order', 'OrderController');

    // Order Type

    // Payment Type
    Route::post('payment/type/status', 'PaymentTypeController@status')->name('status');
    Route::resource('payment/type', 'PaymentTypeController');

    // Payment
    Route::get('payment/paymentType', 'PaymentController@paymentType')->name('paymentType');
    Route::post('payment/status', 'PaymentController@status')->name('status');
    Route::resource('payment', 'PaymentController');

    // Courier
    Route::post('courier/status', 'CourierController@status')->name('status');
    Route::resource('courier', 'CourierController');

    // City
    Route::post('city/status', 'CityController@status')->name('status');
    Route::get('city/courier', 'CityController@courier')->name('courier');
    Route::resource('city', 'CityController');

    // Zone
    Route::get('zone/courier', 'ZoneController@courier')->name('courier');
    Route::get('zone/city', 'ZoneController@city')->name('city');
    Route::post('zone/status', 'ZoneController@status')->name('status');
    Route::resource('zone', 'ZoneController');

    // User
    Route::get('user/users', 'UserController@users')->name('users');
    Route::get('user/role', 'UserController@role')->name('role');
    Route::get('user/status', 'UserController@status')->name('status');
    Route::get('user/login/{id}', function($id) {
       $user = User::find($id);;
        Auth::login($user);
        return redirect()->to('/login');
    })->name('admin');

    Route::resource('user', 'UserController');
    
    

    // Report
    Route::get('report/users', 'Report@users')->name('users');
    Route::get('report/dateCourierUser', 'Report@dateCourierUser')->name('dateCourierUser');
    Route::get('report/getOrdersOnDateCourierUser', 'Report@getOrdersOnDateCourierUser')->name('getOrdersOnDateCourierUser');
    Route::get('report/multipleDateCourierUser', 'Report@multipleDateCourierUser')->name('multipleDateCourierUser');
    Route::get('report/getMultipleDateCourierUser', 'Report@getMultipleDateCourierUser')->name('getMultipleDateCourierUser');
    Route::get('report/dateCourier', 'Report@dateCourier')->name('dateCourier');
    Route::get('report/getDateCourier', 'Report@getDateCourier')->name('getDateCourier');
    Route::get('report/dateUser', 'Report@dateUser')->name('dateUser');
    Route::get('report/getDateUser', 'Report@getDateUser')->name('getDateUser');
    Route::get('report/product', 'Report@product')->name('product');
    Route::get('report/getProduct', 'Report@getProduct')->name('getProduct');
    Route::get('report/payment', 'Report@payment')->name('payment');
    Route::get('report/getPayment', 'Report@getPayment')->name('getPayment');
    Route::get('report/paymentID', 'Report@paymentID')->name('paymentID');
    Route::get('report/paymentType', 'Report@paymentType')->name('paymentType');


    Route::get('menu', function () {
        return view('admin.menu');
    });

    Route::resource('page', 'PageController');
    Route::post('page/status', 'PageController@status')->name('status');

    Route::post('slider/status','SliderController@status');
    Route::get('slider/delete','SliderController@delete')->name('delete');
    Route::resource('slider', 'SliderController');


    Route::post('setting/getSlider', 'SettingController@getSlider')->name('getSlider');
    Route::resource('setting', 'SettingController');


});

Route::group(['as' => 'manager.', 'prefix' => 'manager', 'namespace' => 'Manager', 'middleware' => ['auth', 'manager']], function () {

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('dashboard/getData', 'DashboardController@getData')->name('getData');
    Route::get('dashboard/stockOutProduct', 'DashboardController@stockOutProduct')->name('stockOutProduct');
    Route::get('dashboard/recentUpdate', 'DashboardController@recentUpdate')->name('recentUpdate');
    Route::get('dashboard/getNotification', 'DashboardController@getNotification')->name('getNotification');


    //

    // Products
    Route::get('product/productSync', 'ProductController@productSync')->name('productSync');
    Route::post('product/image', 'ProductController@image')->name('image');
    Route::post('product/status', 'ProductController@status')->name('status');
    Route::resource('product', 'ProductController');

    // Store
    Route::post('store/status', 'StoreController@status')->name('status');
    Route::resource('store', 'StoreController');

    // Supplier

    Route::post('supplier/status', 'SupplierController@status')->name('status');
    Route::resource('supplier', 'SupplierController');

    // Purchase
    Route::get('purchase/supplier', 'PurchaseController@supplier')->name('supplier');
    Route::get('purchase/product', 'PurchaseController@product')->name('product');
    Route::resource('purchase', 'PurchaseController');

    // Product Stock
    Route::resource('stock', 'StockController');

    // Notification
    Route::resource('notification', 'NotificationController');


    // Order



    Route::get('order/deleteAll', 'OrderController@deleteAll')->name('deleteAll');
    Route::get('order/status', 'OrderController@status')->name('status');
    Route::get('order/orderSync', 'OrderController@orderSync')->name('orderSync');
    Route::get('order/view', 'OrderController@view')->name('view');

    Route::get('order/status/{status}', 'OrderController@ordersByStatus')->name('ordersByStatus');
    
    Route::get('order/csvExportRedx/{id}', 'OrderController@csvExportRedx')->name('csvExportRedx');
    Route::get('order/csvExportRedxStore', 'OrderController@csvExportRedxStore')->name('csvExportRedxStore');

    Route::get('order/assign', 'OrderController@assign')->name('assign');
   
    Route::get('order/changeStatusByCheckbox', 'OrderController@changeStatusByCheckbox')->name('changeStatusByCheckbox');
    Route::get('order/getNotes', 'OrderController@getNotes')->name('getNotes');
    Route::get('order/updateNotes', 'OrderController@updateNotes')->name('updateNotes');
    Route::get('order/oldOrders', 'OrderController@oldOrders')->name('oldOrders'); 


    Route::get('order/product', 'OrderController@product')->name('product');
    Route::get('order/stores', 'OrderController@stores')->name('stores');
    Route::get('order/users', 'OrderController@users')->name('users');
    Route::get('order/courier', 'OrderController@courier')->name('courier');
    Route::get('order/city', 'OrderController@city')->name('city');
    Route::get('order/zone', 'OrderController@zone')->name('zone');
    Route::get('order/paymenttype', 'OrderController@paymenttype')->name('paymenttype');
    Route::get('order/paymentnumber', 'OrderController@paymentnumber')->name('paymentnumber');


    Route::get('order/countOrders', 'OrderController@countOrders')->name('countOrders');
    Route::get('order/invoice', 'OrderController@invoice')->name('invoice');
    Route::get('order/storeInvoice', 'OrderController@storeInvoice')->name('storeInvoice');
    Route::get('order/invoice/{id}', 'OrderController@viewInvoice')->name('viewInvoice');
    Route::get('order/memoUpdate', 'OrderController@memoUpdate')->name('memoUpdate');



    // Send Sms
    Route::get('order/sendNumber', 'OrderController@sendNumber')->name('sendNumber');

    Route::resource('order', 'OrderController');

    // Order Type

    // Payment Type
    Route::post('payment/type/status', 'PaymentTypeController@status')->name('status');
    Route::resource('payment/type', 'PaymentTypeController');

    // Payment
    Route::get('payment/paymentType', 'PaymentController@paymentType')->name('paymentType');
    Route::post('payment/status', 'PaymentController@status')->name('status');
    Route::resource('payment', 'PaymentController');

    // Courier
    Route::post('courier/status', 'CourierController@status')->name('status');
    Route::resource('courier', 'CourierController');

    // City
    Route::post('city/status', 'CityController@status')->name('status');
    Route::get('city/courier', 'CityController@courier')->name('courier');
    Route::resource('city', 'CityController');

    // Zone
    Route::get('zone/courier', 'ZoneController@courier')->name('courier');
    Route::get('zone/city', 'ZoneController@city')->name('city');
    Route::post('zone/status', 'ZoneController@status')->name('status');
    Route::resource('zone', 'ZoneController');

    // User
    Route::get('user/users', 'UserController@users')->name('users');
    Route::get('user/role', 'UserController@role')->name('role');
    Route::get('user/status', 'UserController@status')->name('status');
    Route::resource('user', 'UserController');

    // Report
    Route::get('report/users', 'Report@users')->name('users');
    Route::get('report/dateCourierUser', 'Report@dateCourierUser')->name('dateCourierUser');
    Route::get('report/getOrdersOnDateCourierUser', 'Report@getOrdersOnDateCourierUser')->name('getOrdersOnDateCourierUser');
    Route::get('report/multipleDateCourierUser', 'Report@multipleDateCourierUser')->name('multipleDateCourierUser');
    Route::get('report/getMultipleDateCourierUser', 'Report@getMultipleDateCourierUser')->name('getMultipleDateCourierUser');
    Route::get('report/dateCourier', 'Report@dateCourier')->name('dateCourier');
    Route::get('report/getDateCourier', 'Report@getDateCourier')->name('getDateCourier');
    Route::get('report/dateUser', 'Report@dateUser')->name('dateUser');
    Route::get('report/getDateUser', 'Report@getDateUser')->name('getDateUser');
    
    Route::get('report/product', 'Report@product')->name('product');
    Route::get('report/getProduct', 'Report@getProduct')->name('getProduct');
    
    Route::get('report/payment', 'Report@payment')->name('payment');
    Route::get('report/getPayment', 'Report@getPayment')->name('getPayment');
    Route::get('report/paymentID', 'Report@paymentID')->name('paymentID');
    Route::get('report/paymentType', 'Report@paymentType')->name('paymentType');});
    


Route::group(['as' => 'user.', 'prefix' => 'user', 'namespace' => 'User', 'middleware' => ['auth', 'user']], function () {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('dashboard/getData', 'DashboardController@getData')->name('getData');


    // Order

    Route::get('order/deleteAll', 'OrderController@deleteAll')->name('deleteAll');
    Route::get('order/status', 'OrderController@status')->name('status');
    Route::get('order/orderSync', 'OrderController@orderSync')->name('orderSync');
    Route::get('order/view', 'OrderController@view')->name('view');

    Route::get('order/status/{status}', 'OrderController@ordersByStatus')->name('ordersByStatus');

    Route::get('order/assign', 'OrderController@assign')->name('assign');
    Route::get('order/changeStatusByCheckbox', 'OrderController@changeStatusByCheckbox')->name('changeStatusByCheckbox');
    Route::get('order/getNotes', 'OrderController@getNotes')->name('getNotes');
    Route::get('order/updateNotes', 'OrderController@updateNotes')->name('updateNotes');
    Route::get('order/oldOrders', 'OrderController@oldOrders')->name('oldOrders');


    Route::get('order/product', 'OrderController@product')->name('product');
    Route::get('order/stores', 'OrderController@stores')->name('stores');
    Route::get('order/courier', 'OrderController@courier')->name('courier');
    Route::get('order/city', 'OrderController@city')->name('city');
    Route::get('order/zone', 'OrderController@zone')->name('zone');
    Route::get('order/paymenttype', 'OrderController@paymenttype')->name('paymenttype');
    Route::get('order/paymentnumber', 'OrderController@paymentnumber')->name('paymentnumber');

    Route::get('order/complain', 'OrderController@complain')->name('complain');
    Route::get('order/complainOrder', 'OrderController@complainOrder')->name('complainOrder');


    Route::get('order/countOrders', 'OrderController@countOrders')->name('countOrders');
    Route::get('order/invoice', 'OrderController@invoice')->name('invoice');
    Route::get('order/storeInvoice', 'OrderController@storeInvoice')->name('storeInvoice');
    Route::get('order/invoice/{id}', 'OrderController@viewInvoice')->name('viewInvoice');
    Route::get('order/memoUpdate', 'OrderController@memoUpdate')->name('memoUpdate');

    // Send Sms
    Route::get('order/sendNumber', 'OrderController@sendNumber')->name('sendNumber');

    Route::resource('order', 'OrderController');


});


