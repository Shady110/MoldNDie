<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->unsignedBigInteger('code'); // Data type should match the `users` table
            $table->string('name');
            $table->string('continent_name');
            $table->primary('code');
        });
        
        
    }

    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
