<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return datatables()->of(Category::latest()->get())
            ->addColumn('image', function(Category $data) {
                return '<img src="'.asset('/public/product/thumbnail/'.$data->categoryImage).'" alt="image" class="img-fluid avatar-md rounded">';
            })
            ->addColumn('action', function(Category $data) {
                return "<a href='javascript:void(0);' data-id='" .$data->id."' class='action-icon btn-edit'> <i class='fas fa-1x fa-edit'></i></a>
                    <a href='javascript:void(0);' data-id='" .$data->id. "' class='action-icon btn-delete'> <i class='fas fa-trash-alt'></i></a>";

            })
            ->editColumn('categoryName', function(Category $data) {
                return "<a href=".url('product-category/'.$data->categorySlug).">".$data->categoryName."</a>";
            })
            ->editColumn('status', function(Category $data) {
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
        $category = new Category();
        $category->categoryName = $request->categoryName;
        $category->categorySlug = $this->slug($request->categorySlug);
        if($request->categoryImage){
            $category->categoryImage = $request->categoryImage;
        }else{
            $category->categoryImage = 'default.jpg';
        }
        $result = $category->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Category';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Category';
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
        $category = Category::find($id);
        return response()->json($category, 201);
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
        $category = Category::find($id);
        $category->categoryName = $request->categoryName;
        $category->categorySlug = $this->slug($request->categorySlug);
        if($request->categoryImage){
            $category->categoryImage = $request->categoryImage;
        }else{
            $category->categoryImage = 'default.jpg';
        }
        $result = $category->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Category';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Category';
        }
        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $result = $category->delete();
        if($result){
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
        $category = Category::find($request->id);
        $category->status = $request->status;
        $result = $category->save();
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
                Category::find($id)->delete();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Delete Product';
            }
        }else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Product';
        }
        return response()->json($response, 201);
    }

    public function slug($string)
    {
        return  str_replace(' ','-',strtolower($string));
    }
}
