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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            $table->double('amount');
            $table->date('date');
            $table->double('buy_total')->default(0);
            $table->double('discount')->default(0);

            $table->bigInteger('bank_id')->unsigned()->nullable();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');

            $table->double('loyality')->default(0);
            $table->double('paid_amount')->default(0);
            $table->double('balance')->default(0);
            $table->string('payment_type')->default('cash');
            $table->text('card_description')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('shop_id')->nullable();

            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');

            $table->bigInteger('counter_id')->unsigned()->nullable();
            $table->foreign('counter_id')->references('id')->on('counters')->onDelete('cascade ');

            $table->bigInteger('auth_id')->unsigned();
            $table->foreign('auth_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('is_returned_bill')->default('0');
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
        Schema::dropIfExists('bills');
    }
};