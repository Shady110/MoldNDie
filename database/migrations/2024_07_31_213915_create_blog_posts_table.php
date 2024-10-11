<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id('post_id');
            $table->string('title');
            $table->text('introduction');
            $table->text('content');
            $table->string('tags')->nullable();
            $table->string('status')->default('published');
            $table->integer('views')->default(0);
            $table->integer('comments_count')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('user_id'); // Ensure this matches `countries.code`
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        
            $table->unsignedBigInteger('category_id'); // Ensure this matches `countries.code`
            $table->foreign('category_id')->references('category_id')->on('blog_categories')->onDelete('cascade');
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
