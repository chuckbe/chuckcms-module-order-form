<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('chuckcms-module-order-form.coupons.table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id')->nullable()->default(null);
            $table->string('number');
            $table->string('status');
            $table->longText('json');
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
        Schema::dropIfExists(config('chuckcms-module-order-form.coupons.table'));
    }
}
