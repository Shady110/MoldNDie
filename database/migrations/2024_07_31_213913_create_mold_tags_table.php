<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoldTagsTable extends Migration
{
    public function up()
    {
        Schema::create('mold_tags', function (Blueprint $table) {
            $table->foreignId('mold_id')->constrained('molds');
            $table->unsignedBigInteger('tag_id'); // Ensure this is unsignedBigInteger
            $table->primary(['mold_id', 'tag_id']);
            $table->timestamps();

            $table->foreign('tag_id')->references('tag_id')->on('tags')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('mold_tags');
    }
}

