<?php

use Modules\Article\Models\Article;
use Modules\Article\Models\Author;
use Modules\Article\Models\Category;
use Modules\Article\Models\Source;
use Modules\Auth\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

describe('Article Details', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('returns unauthenticated when fetching article details without login', function () {
        $articleId = 1; // Example article ID, change based on your test setup

        $response = $this->getJson("/api/v1/articles/show/{$articleId}"); // Send GET request to the article details endpoint

        $response->assertStatus(Response::HTTP_UNAUTHORIZED) // Check if response status is 401 (Unauthenticated)
            ->assertJson([
                'message' => 'Unauthenticated.', // Check if the unauthenticated message is returned
            ]);
    });

    it('can fetch article details by id', function () {
        // Arrange: Create a Category, Source, and Author
        $category = Category::factory()->create(['name' => 'Tech']);
        $source = Source::factory()->create(['name' => 'Laravel News']);
        $author = Author::factory()->create(['name' => 'John Doe']);

        // Create an article associated with the category, source, and author
        $article = Article::factory()->create([
            'title' => 'Laravel Best Practices',
            'author_id' => $author->id,
            'source_id' => $source->id,
            'category_id' => $category->id,
            'key_words' => 'laravel,best practices,php',
        ]);

        // Act: Send a GET request to the article details endpoint
        $response = actingAs($this->user)->getJson("/api/v1/articles/show/{$article->id}");

        // Assert: Check if the article details are returned correctly
        $response->assertOk()
            ->assertJsonFragment([
                'id' => $article->id,
                'title' => 'Laravel Best Practices',
                'author' => $author->name,
                'source' => $source->name,
                'category' => $category->name,
                'key_words' => 'laravel,best practices,php',
            ]);
    });

    it('returns 404 if article is not found', function () {
        // Act: Try to get an article with an ID that doesn't exist
        $response = actingAs($this->user)->getJson('/api/v1/articles/show/999');

        // Assert: Check that a 404 response is returned
        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'Record not found.',
            ]);
    });

    it('returns the correct response structure for article details', function () {
        // Arrange: Create a Category, Source, Author, and Article
        $category = Category::factory()->create();
        $source = Source::factory()->create();
        $author = Author::factory()->create();
        $article = Article::factory()->create([
            'category_id' => $category->id,
            'source_id' => $source->id,
            'author_id' => $author->id,
        ]);

        // Act: Request the article details
        $response = actingAs($this->user)->getJson("/api/v1/articles/show/{$article->id}");

        // Assert: Check if the response structure matches
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'source', 'category', 'author', 'key_words', 'summary', 'image_url', 'news_url', 'meta',
                ],
            ]);
    });
});
describe('Articles List Test', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('returns unauthenticated when fetching article list without login', function () {
        $response = postJson('/api/v1/articles/list'); // Send POST request to the articles list endpoint

        $response->assertStatus(Response::HTTP_UNAUTHORIZED) // Check if response status is 401 (Unauthenticated)
            ->assertJson([
                'message' => 'Unauthenticated.', // Check if the unauthenticated message is returned
            ]);
    });

    it('can filter articles by title and author', function () {
        // Arrange: Create a Category, Source, and Authors
        $author1 = Author::factory()->create(['name' => 'John Doe']);
        $author2 = Author::factory()->create(['name' => 'Jane Smith']);

        // Create test articles
        Article::factory()->create(['title' => 'Laravel Scout', 'author_id' => $author1->id]);
        Article::factory()->create(['title' => 'Vue.js', 'author_id' => $author2->id]);

        // Act: Send a request to the search endpoint
        $response = actingAs($this->user)->postJson('/api/v1/articles/list', [
            'title' => 'Laravel Scout',
            'author_id' => $author1->id,
        ]);

        // Assert: Check that the correct article is returned
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'title' => 'Laravel Scout',
                'author' => $author1->name,
            ]);
    });

    it('can filter articles by category and source', function () {
        // Arrange: Create Categories, Sources, and Articles
        $category1 = Category::factory()->create(['name' => 'Tech']);
        $category2 = Category::factory()->create(['name' => 'Health']);
        $source1 = Source::factory()->create(['name' => 'TechCrunch']);
        $source2 = Source::factory()->create(['name' => 'HealthLine']);

        Article::factory()->create(['category_id' => $category1->id, 'source_id' => $source1->id]);
        Article::factory()->create(['category_id' => $category2->id, 'source_id' => $source2->id]);

        // Act: Send a request with filter params
        $response = actingAs($this->user)->postJson('/api/v1/articles/list', [
            'category_id' => $category1->id,
            'source_id' => $source1->id,
        ]);

        // Assert: Verify that only the correct articles are returned
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'category' => $category1->name,
                'source' => $source1->name,
            ]);
    });

    it('can filter articles using multiple filters', function () {
        // Arrange: Create multiple Categories, Sources, Authors, and Articles
        $author1 = Author::factory()->create(['name' => 'John Doe']);
        $author2 = Author::factory()->create(['name' => 'Jane Doe']);
        $source1 = Source::factory()->create(['name' => 'TechCrunch']);
        $source2 = Source::factory()->create(['name' => 'Laravel News']);

        Article::factory()->create(['title' => 'PHP News', 'author_id' => $author1->id, 'source_id' => $source1->id]);
        Article::factory()->create(['title' => 'Laravel 9', 'author_id' => $author2->id, 'source_id' => $source2->id]);

        // Act: Filter using title, author, and source
        $response = actingAs($this->user)->postJson('/api/v1/articles/list', [
            'title' => 'PHP News',
            'author_id' => $author1->id,
            'source_id' => $source1->id,
        ]);

        // Assert: Only the matching article should be returned
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'title' => 'PHP News',
                'author' => $author1->name,
                'source' => $source1->name,
            ]);
    });

    it('returns paginated articles and correct response structure', function () {
        // Arrange: Create more than 10 articles for pagination
        Article::factory()->count(15)->create();

        // Act: Send request to get paginated articles
        $response = actingAs($this->user)->postJson('/api/v1/articles/list', []);

        // Assert: Check if pagination and data structure are correct
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'source', 'category', 'author', 'key_words'],
                ],
                'pagination' => [
                    'total', 'count', 'per_page', 'page', 'max_page',
                ],
            ]);
    });

});
