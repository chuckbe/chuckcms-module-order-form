<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCofCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('chuckcms-module-order-form.customers.table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable()->default(null);
            $table->string('surname');
            $table->string('name');
            $table->string('email');
            $table->string('dob')->nullable()->default(null);
            $table->string('tel')->nullable()->default(null);
            $table->longText('json');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop(config('chuckcms-module-order-form.customers.table'));
    }
}
