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
        Schema::create('temp_bills', function (Blueprint $table) {
            $table->id();
            $table->double('total')->default(0);
            $table->bigInteger('status')->default(0);

            $table->bigInteger('auth_id')->unsigned();
            $table->foreign('auth_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');

            $table->bigInteger('counter_id')->unsigned()->nullable();
            $table->foreign('counter_id')->references('id')->on('counters')->onDelete('cascade ');

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
        Schema::dropIfExists('temp_bills');
    }
};
