<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    public function purchase()
    {
        return $this->hasMany('App\Purchase');
    }
    public function categories()
    {
        return $this->belongsToMany('App\Category')->withTimestamps();
    }
    public function media(){
        return $this->belongsToMany('App\Media')->withTimestamps();
    }

    public function order(){
        return $this->belongsToMany('App\Order')->withTimestamps();
    }

    public function url()
    {
        return url('product/'.$this->productSlug.'/'.$this->id);
    }
    public function price()
    {
        if($this->productSalePrice > 0){
            return $this->productSalePrice;
        }else{
            return $this->productRegularPrice;
        }
    }
    public function htmlPrice()
    {
        if($this->productSalePrice > 0){
            return '<div class="product-price-old">
                    <del>
                        ৳ '.number_format($this->productRegularPrice).'
                    </del>
                    <span class="product-price">
                            <strong>
                                ৳ '.number_format($this->productSalePrice).'
                            </strong>
                        </span>
                </div>';
        }else{
            return '<div class="product-price-old">
                        <span class="product-price">
                                <strong>
                                    ৳ '.number_format($this->productRegularPrice).'
                                </strong>
                            </span>
                    </div>';

        }
    }

    public function gallery()
    {
        $gallery = [];
        array_push($gallery,$this->productImage);
        $images = DB::table('media')
            ->join('media_product', 'media.id', '=', 'media_product.media_id')
            ->where('media_product.product_id','=',$this->id)
            ->select('media_product.media_id', 'media.url')
            ->get();
        foreach ($images as $image) {
            array_push($gallery,$image->url);
        }
        return $gallery;
    }
        public function related()
    {
        return $this->belongsToMany('App\Product','related_products','product_id','related_product_id');
    }
}
