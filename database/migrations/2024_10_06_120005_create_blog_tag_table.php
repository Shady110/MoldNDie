<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogTagTable extends Migration
{
    public function up()
    {
        Schema::create('blog_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            // Define foreign keys and cascading on delete
            $table->foreign('post_id')->references('post_id')->on('blog_posts')->onDelete('cascade');
            $table->foreign('tag_id')->references('tag_id')->on('tags')->onDelete('cascade');

            // Set primary key for the pivot table
            $table->primary(['post_id', 'tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_tag');
    }
}
