<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('admin.page.index');
    }


    public function create()
    {
        return DataTables()->of(Page::latest()->get())
            ->addColumn('action', function(Page $data) {
                return "<a href='javascript:void(0);' data-id='" .$data->id."' class='action-icon btn-edit'> <i class='fas fa-1x fa-edit'></i></a>
                    <a href='javascript:void(0);' data-id='" .$data->id. "' class='action-icon btn-delete'> <i class='fas fa-trash-alt'></i></a>";

            })
            ->editColumn('status', function(Page $data) {
                if($data->status == 'Active'){
                    return '<button type="button" class="btn btn-success btn-xs btn-status" data-status="Inactive" name="status" value="'. $data->id . '">Active</button>';
                }else{
                    return '<button type="button" class="btn btn-warning btn-xs btn-status" data-status="Active" name="status" value="'. $data->id . '" >Inactive</button>';
                }
            })
            ->editColumn('pageSlug', function(Page $data) {
                return '<a target="_blank" href="'.url('page/'.$data->pageSlug).'" class="btn btn-warning btn-xs btn-status">View Page</a>';

            })
            ->escapeColumns([])->toJson();
    }


    public function store(Request $request)
    {
        $page = new Page();
        $page->pageTitle = $request->pageTitle;
        $page->pageSlug = $this->slug($request->pageSlug);
        $page->pageContent = $request->pageContent;
        $result = $page->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Page';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Page';
        }
        return response()->json($response, 201);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $page = Page::find($id);
        return response()->json($page, 201);
    }


    public function update(Request $request, $id)
    {
        $page = Page::find($id);
        $page->pageTitle = $request->pageTitle;
        $page->pageSlug = $this->slug($request->pageSlug);
        $page->pageContent = $request->pageContent;
        $result = $page->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully update Page';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to update Page';
        }
        return response()->json($response, 201);
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        $result = $page->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Page';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Page';
        }
        return response()->json($response, 201);
    }
    public function slug($string)
    {
        return  str_replace(' ','-',strtolower($string));
    }
    public function status(Request $request)
    {
        $page = Page::find($request->id);
        $page->status = $request->status;
        $result = $page->save();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Status to '.$request['status'];
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to update Status '.$request['status'];
        }
        return response()->json($response, 201);
    }
}
