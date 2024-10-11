<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoldsTable extends Migration
{
    public function up()
    {
        Schema::create('molds', function (Blueprint $table) {
            $table->id(); // This creates an auto-incrementing UNSIGNED BIGINT (primary key)
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id'); // Ensure this is unsignedBigInteger
            $table->timestamp('upload_date');
            $table->timestamp('update_date')->nullable();
            $table->string('file_path');
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('comments_count')->default(0);
            $table->timestamps();
        
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('molds');
    }
}
