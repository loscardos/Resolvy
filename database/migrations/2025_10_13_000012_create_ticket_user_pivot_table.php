<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_user', function (Blueprint $table) {
            $table->unsignedBigInteger('ticket_id');
            $table->foreign('ticket_id', 'ticket_id_fk_10740417')->references('id')->on('tickets')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_10740417')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
