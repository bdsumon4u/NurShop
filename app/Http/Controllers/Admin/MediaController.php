<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Media;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.media.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return datatables()->of(Media::latest()->get())
            ->addColumn('image', function(Media $media) {
                return '<img src="'.asset('/public/product/thumbnail/'.$media->url).'" alt="image" class="img-fluid avatar-md rounded">';
            })
            ->addColumn('created_at', function(Media $media) {
                return  Carbon::parse($media->created_at)->diffForHumans();
            })
            ->addColumn('action', function(Media $media) {
                return "<a href='javascript:void(0);' data-id='" .$media->id."' class='action-icon btn-edit'> <i class='fas fa-1x fa-edit'></i></a>
                    <a href='javascript:void(0);' data-id='" .$media->id. "' class='action-icon btn-delete'> <i class='fas fa-trash-alt'></i></a>";
            })
            ->only(['id','name','image','created_at','action'])
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
        $image = $request->file('file');
        if (isset($image)) {

            $original = public_path('/');
            $full = public_path('product/');
            $thumbnail = public_path('product/thumbnail/');

            if (!is_dir($full)){
                File::makeDirectory($full,0777,true);
            }
            if (!is_dir($thumbnail)){
                File::makeDirectory($thumbnail,0777,true);
            }

            $imageName = uniqid() . '.jpg';
            $original = $original.$imageName;
            $full = $full.$imageName;
            $thumbnail = $thumbnail.$imageName;

            Image::make($image)->save($original,80);
            Image::make($image)->fit(450, 450)->save($full,80);
            Image::make($image)->fit(180, 200)->save($thumbnail,50);

 
            $media = new Media();
            $media->name = $imageName;
            $media->url = $imageName;
            $result=  $media->save();

            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Successful to upload image';
                $response['url'] = $imageName;

            } else {
                $response['status'] = 'failed';
                $response['message'] = 'Unsuccessful to upload image';
            }

        }else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to upload image';
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $media = Media::find($id);
        return response()->json($media, 201);
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
        $media = Media::find($id);
        $media->name = $request->name;
        $result = $media->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update City';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update City';
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
        if($id){
            $media = Media::find($id);
            $full = public_path('product/'.$media->url);
            $thumbnail = public_path('product/thumbnail/'.$media->url);
            $media->delete();
            File::delete($full);
            File::delete($thumbnail);
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete image';
        }else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete image';
        }
        return response()->json($response, 201);
    }
    public function iframe(Request $request)
    {
        $multiple =  $request->multiple;
        return view('admin.media.iframe',compact('multiple'));
    }
    public function delete(Request $request)
    {
        if($request->ids){
            foreach ($request->ids as $id) {
                $media = Media::find($id);
                $full = public_path('product/'.$media->url);
                $thumbnail = public_path('product/thumbnail/'.$media->url);
                $media->delete();
                File::delete($full);
                File::delete($thumbnail);

                $response['status'] = 'success';
                $response['message'] = 'Successfully Delete image';
            }
        }else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete image';
        }
        return response()->json($response, 201);
    }
    public function iframeget(Request $request)
    {
        $multiple = $_REQUEST['multiple'];
        return datatables()->of(Media::latest()->get())
            ->addColumn('image', function(Media $media) {
                return '<img src="'.asset('/public/product/thumbnail/'.$media->url).'" alt="image" class="img-fluid avatar-md rounded">';
            })
            ->addColumn('created_at', function(Media $media) {
                return  Carbon::parse($media->created_at)->diffForHumans();
            })
            ->addColumn('action', function(Media $media) use ($multiple) {
                return  '<a href="javascript:void(0);" data-path="'.asset('/public/product/thumbnail/'.$media->url).'" data-src="'.$media->url.'" data-multiple="'.$multiple.'" data-id="'.$media->id.'" class="action-icon btn-select"> <i class="fas fa-check-square"></i></a>
                        <a href="javascript:void(0);" data-id="'.$media->id.'" class="action-icon btn-single-delete"> <i class="fas fa-trash"></i></a>';
            })
            ->only(['id','name','image','created_at','action'])
            ->escapeColumns([])->toJson();
    }
}
