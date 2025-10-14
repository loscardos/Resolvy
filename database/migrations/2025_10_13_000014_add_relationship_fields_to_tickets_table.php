<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTicketsTable extends Migration
{
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id', 'customer_fk_10740412')->references('id')->on('customers');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->foreign('subscription_id', 'subscription_fk_10740413')->references('id')->on('subscriptions');
            $table->unsignedBigInteger('ticket_category_id')->nullable();
            $table->foreign('ticket_category_id', 'ticket_category_fk_10740414')->references('id')->on('ticket_categories');
        });
    }
}
