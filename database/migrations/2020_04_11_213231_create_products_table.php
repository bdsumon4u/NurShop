<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('productName');
            $table->string('productCode');
            $table->string('productSlug');
            $table->longText('productDetails');
            $table->string('productRegularPrice')->default(0);
            $table->string('productSalePrice')->default(0);
            $table->string('productImage')->default('default.png');
            $table->string('MetaTitle')->nullable();
            $table->string('MetaKey')->nullable();
            $table->string('MetaDescription')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
