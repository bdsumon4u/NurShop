<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Product;
use App\Purchase;
use App\Stock;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{

    public function index()
    {
        $product = Product::all()->count();
        if ($product>0){
            return view('manager.purchase.index');

        }else{
            return redirect('manager/product');

        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'invoiceID' => 'required',
            'productID' => 'required',
            'supplierID' => 'required',
            'quantity' => 'required'
        ]);
        $purchase = new Purchase();
        $purchase->date = $request['date'];
        $purchase->invoiceID = $request['invoiceID'];
        $purchase->product_id = $request['productID'];
        $purchase->supplier_id = $request['supplierID'];
        $purchase->quantity = $request['quantity'];
        $result = $purchase->save();
        if ($result) {
            $stock =  Stock::query()->where('product_id','=',$request['productID'])->first();
            if($stock){
                $stock->purchase = $stock->purchase + $request['quantity'];
                $stock->stock = $stock->stock + $request['quantity'];
                $stock->save();
            }else{
                $latestStock = new Stock();
                $latestStock->product_id = $request['productID'];
                $latestStock->purchase = $request['quantity'];
                $latestStock->stock = $request['quantity'];
                $latestStock->save();
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Purchase';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Purchase';
        }
        return json_encode($response);
    }

    public function show($id)
    {
        $purchase['data'] = DB::table('purchases')
            ->select( 'purchases.*','suppliers.supplierName','products.productName')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->join('products', 'purchases.product_id', '=', 'products.id')
            ->latest('purchases.created_at')->get();;
        return $purchase;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function edit($id)
    {
        $purchase = DB::table('purchases')
            ->select( 'purchases.*','suppliers.supplierName','products.productName')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->join('products', 'purchases.product_id', '=', 'products.id')
            ->where('purchases.id','=',$id)->first();
        return json_encode($purchase);
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
            'date' => 'required',
            'invoiceID' => 'required',
            'productID' => 'required',
            'supplierID' => 'required',
            'quantity' => 'required'
        ]);

        $purchase = Purchase::find($id);
        $purchase->date = $request['date'];
        $purchase->invoiceID = $request['invoiceID'];
        $purchase->product_id = $request['productID'];
        $purchase->supplier_id = $request['supplierID'];
        $purchase->quantity = $request['quantity'];
        $result = $purchase->save();
        if ($result) {
            $stock =  Stock::query()->where('product_id','=',$request['productID'])->first();
            if($stock){
                $stock->purchase = $stock->purchase - $request['oldQuantity'] + $request['quantity'];
                $stock->stock = $stock->stock  - $request['oldQuantity'] + $request['quantity'];
                $stock->save();
            }else{
                $latestStock = new Stock();
                $latestStock->product_id = $request['productID'];
                $latestStock->purchase = $request['quantity'];
                $latestStock->stock = $request['quantity'];
                $latestStock->save();
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Purchase';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update Purchase';
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
        $purchase = Purchase::find($id);
        $result = Purchase::find($id)->delete();
        if($result){
            $stock =  Stock::query()->where('product_id','=',$purchase->product_id)->first();
            if($stock){
                $stock->purchase = $stock->purchase - $purchase->quantity;
                $stock->save();
            }
             $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Purchase';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Purchase';
        }
        return json_encode($response);
    }
    public function supplier(Request $request)
    {
        if(isset($request['q'])){
            $suppliers = Supplier::query()->where('supplierName','like','%'.$request['q'].'%')->get();
        }else{
            $suppliers = Supplier::all();
        }
        $supplier = array();
        foreach ($suppliers as $item) {
            $supplier[] = array(
                "id" => $item['id'],
                "text" => $item['supplierName']
            );
        }
        return json_encode($supplier);
    }
    public function product(Request $request)
    {
        if(isset($request['q'])){
            $products = Product::query()->where('productName','like','%'.$request['q'].'%')->get();
        }else{
            $products = Product::all();
        }
        $product = array();
        foreach ($products as $item) {
            $product[] = array(
                "id" => $item['id'],
                "text" => $item['productName']
            );
        }
        return json_encode($product);
    }
}
