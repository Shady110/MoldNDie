<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->unsignedBigInteger('user_id'); // Ensure this matches `countries.code`
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('post_id'); // Ensure this matches `countries.code`
            $table->foreign('post_id')->references('post_id')->on('blog_posts')->onDelete('cascade');
            $table->text('content');
            $table->dateTime('date_posted')->useCurrent();
            $table->string('status')->default('approved');
            $table->timestamps();
            });
    }

    public function down()
    {
        Schema::dropIfExists('blog_comments');
    }
}
