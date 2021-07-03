<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrCheckoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_checkout', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('checkout_code');
            $table->integer('id_user');
            $table->string('product_code');
            $table->string('product_name');
            $table->string('brand_name');
            $table->string('category_name');
            $table->integer('price');
            $table->string('status_trx');
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
        Schema::dropIfExists('tr_checkout');
    }
}
