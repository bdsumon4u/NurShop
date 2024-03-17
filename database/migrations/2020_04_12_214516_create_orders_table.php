<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoiceID'); // Invoice ID
            $table->string('web_ID')->nullable(); // Website ID
            $table->string('memo')->nullable(); // Website ID
            $table->integer('payment_type_id')->nullable(); // Payment Type ID
            $table->string('payment_id')->nullable(); // Payment Received Number
            $table->string('paymentAgentNumber')->nullable(); // Payment Sender Number
            $table->integer('courier_id')->nullable(); // Courier ID
            $table->integer('city_id')->nullable(); // City ID
            $table->integer('zone_id')->nullable(); // Zone ID
            $table->integer('subTotal');  // Total
            $table->integer('deliveryCharge')->nullable(); // Delivery Charge
            $table->integer('discountCharge')->nullable(); // Discount Charge
            $table->integer('paymentAmount')->nullable(); // Payment Amount
            $table->date('orderDate');  // Order Date
            $table->date('deliveryDate')->nullable(); // Delivery Date
            $table->date('completeDate')->nullable(); // Complete Date
            $table->string('status')->default('Processing'); // Steps
            $table->integer('user_id');  // User ID
            $table->integer('store_id'); // Store ID
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
        Schema::dropIfExists('orders');
    }
}
