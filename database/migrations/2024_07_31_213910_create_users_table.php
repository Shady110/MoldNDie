<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // Or $table->increments('user_id');
            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->dateTime('date_joined');
            $table->dateTime('last_login');
            $table->string('role');
            $table->unsignedBigInteger('country_code'); // Ensure this matches `countries.code`
            $table->foreign('country_code')->references('code')->on('countries');
        });
        
        
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
