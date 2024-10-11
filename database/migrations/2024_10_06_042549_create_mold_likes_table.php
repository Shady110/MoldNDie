<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoldLikesTable extends Migration
{
    public function up()
    {
        Schema::create('mold_likes', function (Blueprint $table) {
            $table->id('like_id'); // Primary key
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mold_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('mold_id')->references('mold_id')->on('molds')->onDelete('cascade');

            // Ensure unique likes per user-post
            $table->unique(['user_id', 'mold_id']);

        });
    }

    public function down()
    {
        Schema::dropIfExists('mold_likes');
    }
}
