<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_returns', function (Blueprint $table) {
            $table->id();
            $table->date('returned_date');
            $table->double('returned_quantity');
            $table->double('returned_quantity_value')->default(0)->nullable();

            $table->bigInteger('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('id')->on('purchase_invoices')->onDelete('cascade');

            $table->bigInteger('invoice_item_id')->unsigned();
            $table->foreign('invoice_item_id')->references('id')->on('invoice_items')->onDelete('cascade');

            $table->bigInteger('branch_store_id')->unsigned();
            $table->foreign('branch_store_id')->references('id')->on('branch_stores')->onDelete('cascade');

            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');

            $table->bigInteger('auth_id')->unsigned();
            $table->foreign('auth_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('invoice_returns');
    }
};
