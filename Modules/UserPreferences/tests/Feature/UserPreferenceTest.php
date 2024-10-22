<?php

use Modules\Article\Enums\PreferenceTypesEnum;
use Modules\Article\Models\Article;
use Modules\Article\Models\Author;
use Modules\Article\Models\Category;
use Modules\Article\Models\Source;
use Modules\Auth\Models\User;
use Modules\UserPreferences\Models\UserPreference;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

describe('UserPreference Store Test', function () {
    beforeEach(function () {
        // Create a user for authentication
        $this->user = User::factory()->create();

        // Create some preferences for testing
        $this->author = Author::factory()->create();
    });

    it('creates a user preference successfully', function () {
        // Act as the authenticated user
        $response = actingAs($this->user)->postJson('/api/v1/userPreferences', [
            'user_id' => $this->user->id,
            'preference_id' => $this->author->id,
            'preference_type' => PreferenceTypesEnum::AUTHOR->value,  // Example preference type
        ]);  // Pass user for Sanctum authentication

        // Assert: Successful creation
        $response->assertOk() // Expect a 200 Created status
            ->assertJson([
                'message' => 'UserPreference created successfully',  // Check response message
            ]);

        // Optionally, assert that the database has the newly created preference
        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $this->user->id,
            'preference_id' => $this->author->id,
            'preference_type' => PreferenceTypesEnum::AUTHOR->value,
        ]);
    });

    it('returns validation error when required fields are missing', function () {
        // Try to store preference with missing fields
        $response = actingAs($this->user)->postJson('/api/v1/userPreferences', []);

        // Assert: Validation error
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY) // Expect a 422 Unprocessable Entity status
            ->assertJsonValidationErrors(['user_id', 'preference_id', 'preference_type']);
    });

    it('returns unauthenticated when not logged in', function () {
        // Try to create a user preference without authentication
        $response = postJson('/api/v1/userPreferences', [
            'user_id' => $this->user->id,
            'preference_id' => $this->author->id,
            'preference_type' => 'some_type',
        ]);

        // Assert: Should return 401 Unauthorized
        $response->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',  // Check for unauthenticated message
            ]);
    });
});
describe('UserPreference Index Test', function () {
    beforeEach(function () {
        // Create a user for authentication
        $this->user = User::factory()->create();

        // Create some preferences for testing
        $this->author = Author::factory()->create();

        // Mocking User Preferences
        UserPreference::query()->create([
            'user_id' => $this->user->id,
            'preference_id' => $this->author->id,
            'preference_type' => PreferenceTypesEnum::AUTHOR->value,  // Example preference type
        ]);
    });

    it('retrieves a list of user preferences successfully', function () {
        // Act as the authenticated user
        $response = actingAs($this->user)->postJson('/api/v1/userPreferences/list', [
            'user_id' => $this->user->id,
        ]);  // Pass user for Sanctum authentication

        // Assert: Successful retrieval
        $response->assertOk() // Expect a 200 OK status
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'preference_type_name',
                        'preference_value',
                    ],
                ],
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'page',
                    'max_page',
                ],
            ]);

        // Optionally, assert that the response contains the correct user preferences
        $response->assertJsonFragment([
            'preference_type_name' => PreferenceTypesEnum::AUTHOR->name,
            'preference_value' => $this->author->name,
        ]);
    });

    it('returns validation error when user_id is missing', function () {
        // Try to retrieve preferences without passing the required user_id
        $response = actingAs($this->user)->postJson('/api/v1/userPreferences/list', []);

        // Assert: Validation error
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY) // Expect a 422 Unprocessable Entity status
            ->assertJsonValidationErrors(['user_id']);
    });

    it('returns unauthenticated when not logged in', function () {
        // Try to retrieve user preferences without authentication
        $response = postJson('/api/v1/userPreferences/list', [
            'user_id' => $this->user->id,
        ]);

        // Assert: Should return 401 Unauthorized
        $response->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',  // Check for unauthenticated message
            ]);
    });
});

describe('UserPreference News Feed Test', function () {
    beforeEach(function () {
        // Use RefreshDatabase trait to refresh the database between tests
        $this->user = User::factory()->create(); // Create a user for authentication

        // Create authors
        $this->author1 = Author::factory()->create();
        $this->author2 = Author::factory()->create();

        // Create categories
        $this->category1 = Category::factory()->create();
        $this->category2 = Category::factory()->create();

        // Create sources
        $this->source1 = Source::factory()->create();
        $this->source2 = Source::factory()->create();

        // Create some articles with mixed authors, categories, and sources
        $articlesData = [
            ['author_id' => $this->author1->id, 'category_id' => $this->category1->id, 'source_id' => $this->source1->id],
            ['author_id' => $this->author2->id, 'category_id' => $this->category2->id, 'source_id' => $this->source2->id],
            ['author_id' => $this->author1->id, 'category_id' => $this->category2->id, 'source_id' => $this->source1->id],
            ['author_id' => $this->author2->id, 'category_id' => $this->category1->id, 'source_id' => $this->source2->id],
            ['author_id' => $this->author1->id, 'category_id' => $this->category1->id, 'source_id' => $this->source2->id],
        ];

        foreach ($articlesData as $data) {
            Article::factory()->create($data);
        }

        // Create user preferences with a mix of author, category, and source preferences
        $this->userPreferences = collect([
            ['preference_type' => 'author', 'preference_id' => $this->author1->id],
            ['preference_type' => 'author', 'preference_id' => $this->author2->id],
            ['preference_type' => 'category', 'preference_id' => $this->category1->id],
            ['preference_type' => 'category', 'preference_id' => $this->category2->id],
            ['preference_type' => 'source', 'preference_id' => $this->source1->id],
            ['preference_type' => 'source', 'preference_id' => $this->source2->id],
        ])->each(function ($preference) {
            UserPreference::factory()->create([
                'user_id' => $this->user->id,
                'preference_type' => $preference['preference_type'],
                'preference_id' => $preference['preference_id'],
            ]);
        });
    });

    it('fetches the user news feed successfully', function () {
        // Act as the authenticated user
        $response = actingAs($this->user)->postJson('/api/v1/userPreferences/feed', [
            'user_id' => $this->user->id,  // Send user_id in the request
        ]);

        // Assert: Successful fetch
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'source',
                        'author',
                        'category',
                        'key_words',
                    ],
                ],
                'pagination' => ['total', 'count', 'per_page', 'page', 'max_page'],
            ]);

        // Optionally assert that the response includes the expected articles
        $this->assertCount(10, $response->json('data')); // Ensure 10 articles are returned
    });

    it('returns validation error when user_id is missing', function () {
        // Act: Send request without the user_id
        $response = actingAs($this->user)->postJson('/api/v1/userPreferences/feed', []);

        // Assert: Validation error
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['user_id']);
    });

    it('returns unauthenticated when not logged in', function () {
        // Try to fetch feed without authentication
        $response = postJson('/api/v1/userPreferences/feed', [
            'user_id' => $this->user->id,  // Try sending user_id without auth
        ]);

        // Assert: Should return 401 Unauthorized
        $response->assertUnauthorized()
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    });
});
