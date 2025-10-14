<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_code')->unique();
            $table->string('name');
            $table->string('type');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
