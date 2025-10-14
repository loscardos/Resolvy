<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ticket_no')->unique();
            $table->string('subject');
            $table->longText('description')->nullable();
            $table->string('status');
            $table->string('priority')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
