<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        return view('admin.slider.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return DataTables()->of(Slider::latest()->get())
            ->addColumn('image', function(Slider $data) {
                return '<img src="'.asset('/public/product/thumbnail/'.$data->image).'" alt="image" class="img-fluid avatar-xl rounded">';
            })
            ->addColumn('action', function(Slider $data) {

                return "<a href='javascript:void(0);' data-id='" .$data->id."' class='action-icon btn-edit'> <i class='fas fa-1x fa-edit'></i></a>
                    <a href='javascript:void(0);' data-id='" .$data->id. "' class='action-icon btn-delete'> <i class='fas fa-trash-alt'></i></a>";
            })
            ->editColumn('status', function(Slider $data) {
                if($data->status == 'Active'){
                    return '<button type="button" class="btn btn-success btn-xs btn-status" data-status="Inactive" name="status" value="'. $data->id . '">Active</button>';
                }else{
                    return '<button type="button" class="btn btn-warning btn-xs btn-status" data-status="Active" name="status" value="'. $data->id . '" >Inactive</button>';
                }
            })
            ->escapeColumns([])->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $slider = new Slider();
        $slider->name = $request->name;
        $slider->link = $request->link;
        $slider->image = $request->image;
        $result = $slider->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Slider';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Slider';
        }
        return response()->json($response, 201);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $slider = Slider::find($id);
        return response()->json($slider, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::find($id);
        $slider->name = $request->name;
        $slider->link = $request->link;
        $slider->image = $request->image;
        $result = $slider->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Slider';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Slider';
        }
        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::find($id)->delete();
        if($slider){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete City';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete City';
        }
        return response()->json($response, 201);
    }
    public function status(Request $request)
    {
        $slider = Slider::find($request->id);
        $slider->status = $request->status;
        $result = $slider->save();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Status to '.$request['status'];
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to update Status '.$request['status'];
        }
        return response()->json($response, 201);
    }
    public function delete(Request $request)
    {
        if($request->ids){
            foreach ($request->ids as $id) {
                Slider::find($id)->delete();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Delete Slide';
            }
        }else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Slide';
        }
        return response()->json($response, 201);
    }
}
