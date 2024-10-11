<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoldDownloadsTable extends Migration
{
    public function up()
    {
        Schema::create('mold_downloads', function (Blueprint $table) {
            $table->id('download_id');
            $table->foreignId('mold_id')->constrained('molds');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('download_date')->useCurrent();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('mold_downloads');
    }
}
