<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tem_transfers', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('invoice_id')->unsigned()->nullable();
            $table->foreign('invoice_id')->references('id')->on('purchase_invoices')->onDelete('cascade');

            $table->double('quantity');
            $table->double('sell_price');
            $table->double('buy_price');

            $table->bigInteger('invoice_items_id')->unsigned();
            $table->foreign('invoice_items_id')->references('id')->on('invoice_items')->onDelete('cascade');

            $table->bigInteger('auth_id')->unsigned();
            $table->foreign('auth_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
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
        Schema::dropIfExists('tem_transfers');
    }
};
