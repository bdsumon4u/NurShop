<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Slider;
use App\Page;
use App\Settings;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $slug = 'hot-selling-products';
        $topProducts = Product::with('media','categories')->whereHas('categories', function ($query) use ($slug) {
            $query->where('categories.categorySlug','like', $slug);
        })->where('status','Active')->limit(12)->get();
        $otherProducts = Product::with('media','categories')->where('status','Active')->paginate(30);
        $otherProducts->withPath('shop/');
        $slides = Slider::where('status','Active')->limit(3)->get();
        return view('website.home',compact('slug','topProducts','otherProducts','slides'));
    }

    public function product($id)
    {
        $product = Product::with('media','categories','related')->where('products.id','like',$id)->where('status','Active')->first();
        $relatedProducts = Product::with('media','categories')->where('products.id','!=',$product->id)->where('status','Active')->inRandomOrder()->limit(30)->get();
        return view('website.product',compact('product','relatedProducts'));
    }
    public function slugProduct($slugProduct,$id)
    {
        $product = Product::with('media','categories','related')->where('products.id','like',$id)->where('status','Active')->first();
        $relatedProducts = Product::with('media','categories')->where('products.id','!=',$product->id)->where('status','Active')->inRandomOrder()->limit(30)->get();
        return view('website.product',compact('product','relatedProducts'));
    }
    

    public function category($slug)
    {
        $categoryProducts = Product::with('media','categories')->whereHas('categories', function ($query) use ($slug) {
            $query->where('categories.categorySlug','like', $slug);
        })->where('status','Active')->paginate(30);
        $category = Category::where('categories.categorySlug', $slug)->first();
        return view('website.category',compact('category','categoryProducts'));
    }

    public function shop()
    {
        if (isset($_REQUEST['q'])){
            $shop = Product::with('media','categories')->where('products.productName','like',"%{$_REQUEST['q']}%")->where('status','Active')->paginate(30);
        }else{
            $shop = Product::with('media','categories')->where('status','Active')->paginate(30);
        }
        return view('website.shop',compact('shop'));
    }

    public function page($slug)
    {
        $page = Page::where('pageSlug','like',$slug)->first();
        $relatedProducts = Product::with('media','categories')->inRandomOrder()->limit(30)->get();
        return view('website.page',compact('page','relatedProducts'));
    }
    public function loadProducts()
    {

        $otherProducts = Product::with('media','categories')->where('status','Active')->inRandomOrder()->paginate(30);
        foreach($otherProducts as $product) { ?>
          <div class="col-md-2 col-6">
            <div href="#" class="card card-product-grid product-box-2">
              <a href="<?php  echo url('/product/'.$product->id)  ?>" class="img-wrap">
                <img class="img-fit lazyload"   src="<?php echo asset('public/product/thumbnail/default.jpg') ?>"  data-src="<?php echo asset('/public/product/thumbnail/'.$product->productImage)  ?>" alt="<?php echo $product->productName  ?>">
              </a>
              <figcaption class="info-wrap">
                <a href="<?php echo url('/product/'.$product->id)  ?>" class="title text-truncate"><?php echo $product->productName  ?></a>
                <div class="price mt-1 text-center">
                    <?php echo $product->htmlPrice() ?>
                </div>
              </figcaption>
              <button class="btn btn-success btn-sm btn-block" onclick="addToCart(<?php echo $product->id ?>)">
                <i class="fa fa-shopping-cart"  aria-hidden="true"></i> অর্ডার করুন
              </button>
            </div>
          </div>
        <?php }
    }
}
