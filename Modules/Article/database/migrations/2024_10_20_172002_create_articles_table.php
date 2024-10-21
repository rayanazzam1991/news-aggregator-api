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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('source');
            $table->string('category')->nullable();
            $table->string('author')->nullable();
            $table->string('key_words')->nullable();
            $table->string('summary')->nullable();
            $table->string('image_url')->nullable();
            $table->string('news_url')->nullable();
            $table->json('meta')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
