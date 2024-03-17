<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Store;
use App\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.supplier.index');
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
            'supplierName' => 'required',
            'supplierPhone' => 'required'
        ]);

        $supplier = new Supplier();
        $supplier->supplierName = $request['supplierName'];
        $supplier->supplierPhone = $request['supplierPhone'];
        $result = $supplier->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Supplier';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Supplier';
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
        $supplier['data'] = Supplier::latest()->get();
        return $supplier;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return json_encode($supplier);
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
            'supplierName' => 'required',
            'supplierPhone' => 'required'
        ]);

        $supplier = Supplier::find($id);
        $supplier->supplierName = $request['supplierName'];
        $supplier->supplierPhone = $request['supplierPhone'];
        $result = $supplier->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Supplier';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update Supplier';
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
        $result = Supplier::find($id)->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Supplier';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Supplier';
        }
        return json_encode($response);
    }
    public function status(Request $request)
    {
        $supplier = Supplier::find($request['id']);
        $supplier->status = $request['status'];
        $result = $supplier->save();
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
