<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tem_orders', function (Blueprint $table) {
            $table->id();
            $table->double('quantity');
            
            $table->double('sell_price');
            $table->double('buy_price');

            $table->double('discount')->default(0);

            $table->bigInteger('branch_store_id')->unsigned();
            $table->foreign('branch_store_id')->references('id')->on('branch_stores')->onDelete('cascade');

            $table->bigInteger('invoice_items_id')->unsigned();
            $table->foreign('invoice_items_id')->references('id')->on('invoice_items')->onDelete('cascade');

            $table->bigInteger('auth_id')->unsigned();
            $table->foreign('auth_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');

            $table->bigInteger('temp_bill_id')->unsigned();
            $table->foreign('temp_bill_id')->references('id')->on('temp_bills')->onDelete('cascade');

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
        Schema::dropIfExists('tem_orders');
    }
}