<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTicketStatusHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('ticket_status_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->foreign('ticket_id', 'ticket_fk_10740465')->references('id')->on('tickets');
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->foreign('updated_by_id', 'updated_by_fk_10740468')->references('id')->on('users');
        });
    }
}
