<?php

use Modules\Article\Models\Author;
use Modules\Auth\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;

describe('Author Get List Test', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });
    it('retrieves a paginated list of authors', function () {
        // Arrange: Create multiple authors
        Author::factory()->count(5)->create();

        // Act: Make a GET request to the /authors endpoint
        $response = actingAs($this->user)->getJson('/api/v1/authors');

        // Assert: Check that the response is OK and includes pagination details
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name'],
                ],
                'pagination' => ['total', 'count', 'per_page', 'page', 'max_page'],
            ]);
    });

    it('fails to retrieve list of authors when unauthenticated', function () {
        // Act: Make a GET request without authentication
        $response = $this->getJson('/api/v1/authors');

        // Assert: Expect 401 Unauthorized
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    });
});
describe('Author Create Test', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });
    it('creates a new author', function () {
        // Arrange: Prepare valid author data
        $authorData = ['name' => 'New Author'];

        // Act: Make a POST request to create a new author
        $response = actingAs($this->user)->postJson('/api/v1/authors', $authorData);

        // Assert: Check the author was created successfully
        $response->assertOk()
            ->assertJson([
                'message' => 'Author created successfully',
            ]);

        // Check that the author exists in the database
        $this->assertDatabaseHas('authors', ['name' => 'New Author']);
    });

    it('returns 422 when validation fails', function () {
        // Act: Make a POST request with invalid data (missing name)
        $response = actingAs($this->user)->postJson('/api/v1/authors', []);

        // Assert: Expect validation error (422 Unprocessable Entity)
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    });

    it('fails to create author when unauthenticated', function () {
        // Act: Make a POST request without authentication
        $response = $this->postJson('/api/v1/authors', ['name' => 'New Author']);

        // Assert: Expect 401 Unauthorized
        $response->assertStatus(401);
    });
});
describe('Author Update Test', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });
    it('updates an existing author', function () {
        // Arrange: Create an author
        $author = Author::factory()->create(['name' => 'Old Name']);

        // Act: Make a PUT request to update the author's name
        $response = actingAs($this->user)->putJson("/api/v1/authors/{$author->id}", ['name' => 'Updated Name']);

        // Assert: Check the author was updated
        $response->assertOk()
            ->assertJson(['message' => 'Author updated successfully']);

        // Check the database has the updated name
        $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'Updated Name']);
    });

    it('returns 404 when trying to update non-existing author', function () {
        // Act: Make a PUT request for a non-existing author
        $response = actingAs($this->user)->putJson('/api/v1/authors/999', ['name' => 'Updated Name']);

        // Assert: Expect 404 Not Found
        $response->assertStatus(404);
    });

    it('fails to update author when unauthenticated', function () {
        // Arrange: Create an author
        $author = Author::factory()->create();

        // Act: Make a PUT request without authentication
        $response = $this->putJson("/api/v1/authors/{$author->id}", ['name' => 'Updated Name']);

        // Assert: Expect 401 Unauthorized
        $response->assertStatus(401);
    });
});
describe('Author Delete Test', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });
    it('soft deletes an author', function () {
        // Arrange: Create an author
        $author = Author::factory()->create();

        // Act: Make a DELETE request to remove the author
        $response = actingAs($this->user)->deleteJson("/api/v1/authors/{$author->id}");

        // Assert: Check the author was soft deleted
        $response->assertOk()
            ->assertJson(['message' => 'Author deleted successfully']);

        // Check that the author is soft deleted (exists in the database but is "deleted")
        $this->assertSoftDeleted('authors', ['id' => $author->id]);
    });

    it('returns 404 when trying to delete non-existing author', function () {
        // Act: Make a DELETE request for a non-existing author
        $response = actingAs($this->user)->deleteJson('/api/v1/authors/999');

        // Assert: Expect 404 Not Found
        $response->assertStatus(404);
    });

    it('fails to delete author when unauthenticated', function () {
        // Arrange: Create an author
        $author = Author::factory()->create();

        // Act: Make a DELETE request without authentication
        $response = $this->deleteJson("/api/v1/authors/{$author->id}");

        // Assert: Expect 401 Unauthorized
        $response->assertStatus(401);
    });
});
