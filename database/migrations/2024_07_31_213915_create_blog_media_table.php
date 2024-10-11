<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogMediaTable extends Migration
{
    public function up()
    {
        Schema::create('blog_media', function (Blueprint $table) {
            $table->id('media_id');
            $table->string('media_type'); // 'image' or 'video'
            $table->string('media_path');
            $table->timestamps();
            $table->unsignedBigInteger('post_id'); // Ensure this matches `countries.code`
            $table->foreign('post_id')->references('post_id')->on('blog_posts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_media');
    }
}
