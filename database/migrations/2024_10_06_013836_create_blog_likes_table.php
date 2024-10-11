<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_likes', function (Blueprint $table) {
            $table->bigIncrements('like_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('post_id')->references('post_id')->on('blog_posts')->onDelete('cascade');

            // Ensure unique likes per user-post
            $table->unique(['user_id', 'post_id']);
        });

        // Add likes_count column to posts table if not already added
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->integer('likes_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_likes');

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('likes_count');
        });
    }
}
