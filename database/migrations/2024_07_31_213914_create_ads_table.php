<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id('ad_id');
            $table->string('title');
            $table->text('content');
            $table->string('image_path')->nullable();
            $table->string('link')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
