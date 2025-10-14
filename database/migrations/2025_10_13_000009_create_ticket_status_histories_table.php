<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketStatusHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_status_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('from_status');
            $table->string('to_status');
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
