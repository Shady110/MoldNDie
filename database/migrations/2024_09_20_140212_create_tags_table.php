<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('post_id'); // Add the post_id column with bigint type

            // Add foreign key constraint
            $table->foreign('post_id')
                ->references('post_id')
                ->on('blog_posts')
                ->onDelete('cascade'); // Cascading delete

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dropping foreign key before dropping the table
        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
        });

        Schema::dropIfExists('tags');
    }
};
