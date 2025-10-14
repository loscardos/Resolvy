<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicePackagesTable extends Migration
{
    public function up()
    {
        Schema::create('service_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('bandwidth_mbps_down');
            $table->string('bandwidth_mbps_up');
            $table->decimal('price', 15, 2)->nullable();
            $table->longText('description')->nullable();
            $table->string('is_active')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
