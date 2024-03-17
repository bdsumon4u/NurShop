<?php

namespace App\Http\Controllers\Manager;
use App\Category;
use App\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\DataTables;
use App\Http\Controllers\Controller;
use App\Product;
use App\Stock;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class ProductController extends Controller
{

    public function index()
    {
        $store = Store::all()->count();
        if($store>0){
            return view('manager.product.index');
        }else{
            return redirect('manager/store');
        }
    }

    public function store(Request $request)
    {

        $product = new Product();
        $product->productName = $request->productName;
        $product->productSlug = $this->slug($request->productSlug);
        $product->productCode = $request->productCode;
        $product->productDetails = $request->productDetails;
        $product->productRegularPrice = $request->productRegularPrice;
        $product->productSalePrice = $request->productSalePrice;
        $product->MetaTitle = $request->MetaTitle;
        $product->MetaKey = $request->MetaKeywords;
        $product->MetaDescription = $request->MetaDescription;
        $product->productImage = $request->imageID;
        $result = $product->save();
        $product->categories()->attach($request->productCategory);
        $product->media()->attach($request->gallery);
        if ($result) {
            $latestStock = new Stock();
            $latestStock->product_id = $product->id;
            $latestStock->purchase = 0;
            $latestStock->stock = 0;
            $latestStock->save();
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Product';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Product';
        }
        return response()->json($response, 201);
    }

    public function show(Request $request)
    {

        return DataTables::of(Product::with('media','categories')->get())
            ->editColumn('productImage', function ($product) {
                return "<img src='".asset('/public/product/thumbnail/'.$product->productImage)."' class='img-fluid avatar-lg rounded'>";
            })
            ->editColumn('ProductName', function ($product) {
                return "<a href=".url('product/'.$product->ProductSlug).">".$product->ProductName."</a>";
            })
            ->addColumn('status', function ($product) {
                if ($product->status == 'Active') {
                    return '<button type="button" class="btn btn-success btn-xs btn-status" data-status="Inactive" name="status" value="'.$product->id.'">Active</button>';
                }else{
                    return '<button type="button" class="btn btn-warning btn-xs btn-status" data-status="Active" name="status" value="'.$product->id.'" >Inactive</button>';
                }
            })
            ->addColumn('categories', function ($product) {
                $CategoryName = array();
                foreach ($product->categories as $category){
                    array_push($CategoryName,$category->categoryName);
                }
                return implode(', ', $CategoryName);
            })
            ->addColumn('action', function ($product) {
                return "<a href='javascript:void(0);' data-id='" .$product->id."' class='action-icon btn-edit'> <i class='fas fa-1x fa-edit'></i></a>";
            })
            ->escapeColumns([])->make();

    }

    public function edit($id)
    {
        $product = Product::with('media','categories')->where('products.id','=',$id)->first();
        return json_encode($product);
    }

    public function update(Request $request, $id)
    {

        $product = Product::find($id);
        $product->productName = $request->productName;
        $product->productSlug = $this->slug($request->productSlug);
        $product->productCode = $request->productCode;
        $product->productDetails = $request->productDetails;
        $product->productRegularPrice = $request->productRegularPrice;
        $product->productSalePrice = $request->productSalePrice;
        $product->MetaTitle = $request->MetaTitle;
        $product->MetaKey = $request->MetaKeywords;
        $product->MetaDescription = $request->MetaDescription;
        $product->productImage = $request->imageID;
        $result = $product->save();

        $product->categories()->sync($request->productCategory);
        $product->media()->sync($request->gallery);
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Product';

        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Product';
        }
        return response()->json($response, 201);
    }

    public function destroy($id)
    {
        $result = Product::find($id)->delete();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Product';
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Product';
        }
        return json_encode($response);
    }

    public function image(Request $request)
    {

        $image = $request['productImage'];
        if (isset($image)) {
            if (!Storage::disk('public')->exists('product')) {
                Storage::disk('public')->makeDirectory('product');
            }
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $result = $request->productImage->move(public_path('product'), $imageName);
            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Successful to upload image';
                $response['url'] = $imageName;

            } else {
                $response['status'] = 'failed';
                $response['message'] = 'Unsuccessful to upload Product';
            }

        }else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to upload Product';
        }
        return json_encode($response);
    }

    public function status(Request $request)
    {
        $product = Product::find($request['id']);
        $product->status = $request['status'];
        $result = $product->save();
        if($result){
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Status to '.$request['status'];
        }else{
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to update Status '.$request['status'];
        }
        return json_encode($response);
    }

    public function category(Request $request)
    {
        if(isset($request['q'])){
            $couriers = Category::query()->where('categoryName','like','%'.$request['q'].'%')->get();
        }else{
            $couriers = Category::all();
        }
        $courier = array();
        foreach ($couriers as $item) {
            $courier[] = array(
                "id" => $item['id'],
                "text" => $item['categoryName']
            );
        }
        return json_encode($courier);
    }

    public function delete(Request $request)
    {
        if($request->ids){
            foreach ($request->ids as $id) {
                Product::find($id)->delete();
                $response['status'] = 'success';
                $response['message'] = 'Successfully Delete Product';
            }
        }else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Product';
        }
        return response()->json($response, 201);
    }


    public function productSync(Request $request)
    {
        set_time_limit(0);

        if(empty($_REQUEST['url'])) return;

        $syncProducts = json_decode($this->getProducts($_REQUEST['url']));
        $orderCount = 0;
        foreach ($syncProducts as $syncProduct){
            $checkProduct = Product::query()->where('productCode','=',$syncProduct->get_sku)->first();
            if(empty($checkProduct)) {

                $image = $syncProduct->image;
                $full = public_path('product/');
                $thumbnail = public_path('product/thumbnail/');
                if (!is_dir($full)){
                    File::makeDirectory($full,0777,true);
                }
                if (!is_dir($thumbnail)){
                    File::makeDirectory($thumbnail,0777,true);
                }

                $imageName = uniqid() . '.jpg';
                $full = $full.$imageName;
                $thumbnail = $thumbnail.$imageName;


                $img = Image::make(file_get_contents($image))->fit(450, 450)->save($full);
                $img = Image::make(file_get_contents($image))->fit(180, 200)->save($thumbnail);


                $media = new Media();
                $media->name = $imageName;
                $media->url = $imageName;
                $media->save();
                $newProduct = new Product();

                $newProduct->ProductName = htmlspecialchars($syncProduct->get_name);
                $newProduct->productSlug = $this->slug(htmlspecialchars($syncProduct->get_name));
                $newProduct->productCode = $syncProduct->get_sku;
                $newProduct->ProductDetails = $syncProduct->get_description;
                $newProduct->ProductRegularPrice = $syncProduct->get_regular_price;
                $newProduct->ProductSalePrice = $syncProduct->get_sale_price;
                $newProduct->ProductImage = $imageName;
                $newProduct->save();

                foreach ($syncProduct->get_categories as $categories) {
                    $category = Category::query()->where('CategorySlug', '=', $categories->slug)->first();
                    if (!empty($category)) {
                        DB::table('category_product')->insert([
                            'product_id' => $newProduct->id,
                            'category_id' => $category->id
                        ]);

                    } else {
                        $category = new Category();
                        $category->categoryName = $categories->name;
                        $category->categorySlug = $categories->slug;
                        $category->categoryImage = 'default.jpg';
                        $result = $category->save();
                        if ($result) {
                            DB::table('category_product')->insert([
                                'product_id' => $newProduct->id,
                                'category_id' => $category->id,
                            ]);
                        }
                    }
                }
                $orderCount++;

            }
        }
        if($orderCount > 0){
            $response['status'] = 'success';
            $response['products'] = $orderCount;
        }else{
            $response['status'] = 'failed';
            $response['products'] = $orderCount;
        }
        return json_encode($response);
    }

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
    public function slug($string)
    {
        return  str_replace(' ','-',strtolower($string));
    }

}
