<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoldMediaTable extends Migration
{
    public function up()
    {
        Schema::create('mold_media', function (Blueprint $table) {
            $table->id('media_id');
            $table->foreignId('mold_id')->constrained('molds');
            $table->string('media_type'); // 'image' or 'video'
            $table->string('media_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mold_media');
    }
}
