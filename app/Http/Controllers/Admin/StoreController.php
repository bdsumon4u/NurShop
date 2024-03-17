<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.store.index');
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
            'storeName' => 'required',
            'storeUrl' => 'required',
            'storeDetails' => 'required'
        ]);

        $store = new Store();
        $store->storeName = $request['storeName'];
        $store->storeUrl = $request['storeUrl'];
        $store->storeDetails = $request['storeDetails'];
        $result = $store->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Store';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Store';
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
        $store['data'] = Store::latest()->get();
        return $store;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return false|string
     */
    public function edit($id)
    {
        $store = Store::find($id);
        return json_encode($store);
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
            'storeName' => 'required',
            'storeUrl' => 'required'
        ]);

        $store = Store::find($id);
        $store->storeName = $request['storeName'];
        $store->storeUrl = $request['storeUrl'];
        $store->storeDetails = $request['storeDetails'];
        $result = $store->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Store';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update Store';
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
        $result = Store::find($id)->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Store';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Store';
        }
        return json_encode($response);
    }
    public function status(Request $request)
    {
        $store = Store::find($request['id']);
        $store->status = $request['status'];
        $result = $store->save();
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
