<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('subscription_id');
            $table->unsignedBigInteger('user_id'); // Ensure this matches `countries.code`
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('email')->unique();
            $table->dateTime('subscribe_date')->useCurrent();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
