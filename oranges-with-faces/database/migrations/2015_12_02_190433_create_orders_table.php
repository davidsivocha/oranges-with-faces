<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function($table) {
            $table->increments('id');
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('shipping_name');
            $table->string('shipping_address_1');
            $table->string('shipping_address_2');
            $table->string('shipping_city');
            $table->string('shipping_county');
            $table->string('shipping_post_code');
            $table->string('status');
            $table->string('charge_id');
            $table->integer('total_cost');
            $table->string('currency');
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
        Schema::drop('orders');
    }
}
