<?php

use Modules\Article\Models\Article;
use Modules\Auth\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

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
        // Arrange: Create test articles
        Article::factory()->create(['title' => 'Laravel Scout', 'author' => 'John Doe']);
        Article::factory()->create(['title' => 'Vue.js', 'author' => 'Jane Smith']);

        // Act: Send a request .to the search endpoint
        $response = actingAs($this->user)->postJson('/api/v1/articles/list', [
            'title' => 'Laravel Scout',
            'author' => 'John Doe',
        ]);

        // Assert: Check that the correct article is returned
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'title' => 'Laravel Scout',
                'author' => 'John Doe',
            ]);
    });

    it('can filter articles by category and source', function () {
        // Arrange: Create articles with different categories and sources
        Article::factory()->create(['category' => 'Tech', 'source' => 'TechCrunch']);
        Article::factory()->create(['category' => 'Health', 'source' => 'HealthLine']);

        // Act: Send a request with filter params
        $response = actingAs($this->user)->postJson('/api/v1/articles/list', [
            'category' => 'Tech',
            'source' => 'TechCrunch',
        ]);

        // Assert: Verify that only the correct articles are returned
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'category' => 'Tech',
                'source' => 'TechCrunch',
            ]);
    });

    it('can filter articles using multiple filters', function () {
        // Arrange: Create multiple articles with different attributes
        Article::factory()->create(['title' => 'PHP News', 'author' => 'John Doe', 'source' => 'TechCrunch']);
        Article::factory()->create(['title' => 'Laravel 9', 'author' => 'Jane Doe', 'source' => 'Laravel News']);

        // Act: Filter using title and author
        $response = actingAs($this->user)->postJson('/api/v1/articles/list', [
            'title' => 'PHP News',
            'author' => 'John Doe',
            'source' => 'TechCrunch',
        ]);

        // Assert: Only the matching article should be returned
        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'title' => 'PHP News',
                'author' => 'John Doe',
                'source' => 'TechCrunch',
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
