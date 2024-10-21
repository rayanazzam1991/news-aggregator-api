<?php

use Modules\Article\Models\Article;
use Modules\Auth\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;

describe('Article Details', function () {

    beforeEach(function () {
        $this->user = User::factory()->create();
    });
    it('returns unauthenticated when fetching article details without login', function () {
        $articleId = 1; // Example article ID, change based on your test setup

        $response = $this->getJson("/api/v1/articles/{$articleId}"); // Send GET request to the article details endpoint

        $response->assertStatus(Response::HTTP_UNAUTHORIZED) // Check if response status is 401 (Unauthenticated)
            ->assertJson([
                'message' => 'Unauthenticated.', // Check if the unauthenticated message is returned
            ]);
    });

    it('can fetch article details by id', function () {
        // Arrange: Create an article
        $article = Article::factory()->create([
            'title' => 'Laravel Best Practices',
            'author' => 'John Doe',
            'source' => 'Laravel News',
            'category' => 'Tech',
            'key_words' => 'laravel,best practices,php',
        ]);

        // Act: Send a GET request to the article details endpoint
        $response = actingAs($this->user)->getJson("/api/v1/articles/{$article->id}");

        // Assert: Check if the article details are returned correctly
        $response->assertOk()
            ->assertJsonFragment([
                'id' => $article->id,
                'title' => 'Laravel Best Practices',
                'author' => 'John Doe',
                'source' => 'Laravel News',
                'category' => 'Tech',
                'key_words' => 'laravel,best practices,php',
            ]);
    });

    it('returns 404 if article is not found', function () {
        // Act: Try to get an article with an ID that doesn't exist
        $response = actingAs($this->user)->getJson('/api/v1/articles/999');

        // Assert: Check that a 404 response is returned
        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'Record not found.',
            ]);
    });

    it('returns the correct response structure for article details', function () {
        // Arrange: Create an article
        $article = Article::factory()->create();

        // Act: Request the article details
        $response = actingAs($this->user)->getJson("/api/v1/articles/{$article->id}");

        // Assert: Check if the response structure matches
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'source', 'category', 'author', 'key_words', 'summary', 'image_url', 'news_url', 'meta',
                ],
            ]);
    });
});
