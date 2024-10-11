<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id(); // This creates an auto-incrementing UNSIGNED BIGINT (primary key)            $table->unsignedBigInteger('user_id');
            $table->text('bio')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('social_media_links')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id'); // Ensure this matches `countries.code`
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');});
        
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
