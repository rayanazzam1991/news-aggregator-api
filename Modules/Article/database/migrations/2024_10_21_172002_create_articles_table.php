<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Article\Models\Author;
use Modules\Article\Models\Category;
use Modules\Article\Models\Source;

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
            $table->foreignIdFor(Source::class)->nullable()->constrained('sources');
            $table->foreignIdFor(Category::class)->nullable()->constrained('categories');
            $table->foreignIdFor(Author::class)->nullable()->constrained('authors');
            $table->string('key_words')->nullable();
            $table->text('summary')->nullable();
            $table->string('image_url')->nullable();
            $table->string('news_url')->nullable();
            $table->dateTime('published_at')->nullable();
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
