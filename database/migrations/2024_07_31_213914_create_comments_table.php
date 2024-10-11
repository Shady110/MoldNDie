<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->foreignId('mold_id')->constrained('molds');
            $table->text('content');
            $table->dateTime('date_posted')->useCurrent();
            $table->string('status')->default('approved');
            $table->timestamps();
            $table->unsignedBigInteger('user_id'); // Ensure this matches `countries.code`
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
