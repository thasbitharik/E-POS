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
        Schema::create('cash_in_histories', function (Blueprint $table) {
            $table->id();
            $table->string('cash_in_type');
            $table->double('amount');
            $table->date('date');

            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');

            $table->bigInteger('staff_id')->unsigned()->nullable();
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('counter_id')->unsigned()->nullable();
            $table->foreign('counter_id')->references('id')->on('counters')->onDelete('cascade');

            $table->bigInteger('income_type_id')->unsigned();
            $table->foreign('income_type_id')->references('id')->on('income_types')->onDelete('cascade');

            $table->bigInteger('income_id')->unsigned();
            $table->foreign('income_id')->references('id')->on('incomes')->onDelete('cascade');

            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('cash_in_histories');
    }
};
