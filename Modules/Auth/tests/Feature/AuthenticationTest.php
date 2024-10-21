<?php

use Illuminate\Support\Facades\Password;
use Modules\Auth\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

describe('Register Test', function () {
    /**
     * Test registration with valid data
     */
    it('registers a user with valid data', function () {
        // Arrange: Create the data to be sent in the request
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        // Act: Send the request to the API
        $response = postJson('/api/v1/auth/register', $data);

        // Assert: Check if the user was successfully created and the response is correct
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Registration successful',
            ]);

        // Check if the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
        ]);

        // Verify password is hashed correctly
        $user = User::where('email', 'johndoe@example.com')->first();
        expect(Hash::check('Password123!', $user->password))->toBeTrue();
    });

    /**
     * Test registration with missing or invalid data
     */
    it('fails to register a user with invalid data', function ($invalidData, $expectedErrorField) {
        // Act: Send the invalid data to the API
        $response = postJson('/api/v1/auth/register', $invalidData);

        // Assert: Check for validation error
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    $expectedErrorField,
                ],
            ]);
    })->with([
        [['name' => '', 'email' => 'johndoe@example.com', 'password' => 'Password123!', 'password_confirmation' => 'Password123!'], 'name'], // Name required
        [['name' => 'John Doe', 'email' => '', 'password' => 'Password123!', 'password_confirmation' => 'Password123!'], 'email'], // Email required
        [['name' => 'John Doe', 'email' => 'johndoe@example.com', 'password' => '', 'password_confirmation' => 'Password123!'], 'password'], // Password required
        [['name' => 'John Doe', 'email' => 'johndoe@example.com', 'password' => 'Password123!', 'password_confirmation' => ''], 'password_confirmation'], // Password confirmation required
    ]);

    /**
     * Test registration with password mismatch
     */
    it('fails to register a user if password confirmation does not match', function () {
        // Arrange: Create the data where passwords do not match
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password321!',
        ];

        // Act: Send the request
        $response = postJson('/api/v1/auth/register', $data);

        // Assert: Validation error for mismatched passwords
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password',
                ],
            ]);
    });

    /**
     * Test registration with weak password
     */
    it('fails to register a user if password does not meet the security requirements', function () {
        // Arrange: Create the data with a weak password
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'weakpassword', // No mixed case, numbers, or symbols
            'password_confirmation' => 'weakpassword',
        ];

        // Act: Send the request
        $response = postJson('/api/v1/auth/register', $data);

        // Assert: Validation error for weak password
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password',
                ],
            ]);
    });
});

describe('Login Test', function () {
    /**
     * Test login with valid credentials
     */
    it('logs in a user with valid credentials', function () {
        // Arrange: Create a user in the database
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        // Prepare the login data
        $loginData = [
            'email' => 'johndoe@example.com',
            'password' => 'Password123!',
        ];

        // Act: Send the request to the login API
        $response = postJson('/api/v1/auth/login', $loginData);

        // Assert: Check if the login was successful and a token was returned
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'token_type',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                ],
            ]);
    });

    /**
     * Test login with invalid credentials
     */
    it('fails to log in a user with incorrect password', function () {
        // Arrange: Create a user in the database
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        // Prepare login data with incorrect password
        $loginData = [
            'email' => 'johndoe@example.com',
            'password' => 'WrongPassword',
        ];

        // Act: Send the request to the login API
        $response = postJson('/api/v1/auth/login', $loginData);

        // Assert: Check if the login fails with validation error
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'credentials',
                ],
            ])
            ->assertJson([
                'message' => 'The provided credentials are incorrect.',
            ]);
    });

    /**
     * Test login with missing required fields
     */
    it('fails to log in when required fields are missing', function ($loginData, $expectedErrorField) {
        // Act: Send the login request with missing fields
        $response = postJson('/api/v1/auth/login', $loginData);

        // Assert: Check for validation error
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    $expectedErrorField,
                ],
            ]);
    })->with([
        [['email' => '', 'password' => 'Password123!'], 'email'], // Missing email
        [['email' => 'johndoe@example.com', 'password' => ''], 'password'], // Missing password
    ]);

    /**
     * Test login with non-existent email
     */
    it('fails to log in a user with non-existent email', function () {
        // Arrange: Email that does not exist in the database
        $loginData = [
            'email' => 'nonexistent@example.com',
            'password' => 'Password123!',
        ];

        // Act: Send the login request
        $response = postJson('/api/v1/auth/login', $loginData);

        // Assert: Validation error for non-existent user
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                ],
            ])
            ->assertJson([
                'message' => 'The selected email is invalid.',
            ]);
    });
});

describe('Logout Test', function () {
    it('logs out the authenticated user successfully', function () {
        // Create a new user
        $user = User::factory()->create();

        // Make a POST request to the logout endpoint
        $response = actingAs($user)->postJson('/api/v1/auth/logout');

        // Assert that the response has a status code of 200 (OK)
        $response->assertStatus(Response::HTTP_OK);

        // Assert that the success message is returned
        $response->assertJson([
            'message' => 'Logout successfully',
        ]);
    });

    it('returns 401 when no user is authenticated', function () {
        // Make a POST request to the logout endpoint without authentication
        $response = postJson('/api/v1/auth/logout');

        // Assert that the response has a status code of 401 (Unauthorized)
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        // Assert that the proper error message is returned
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });
});

describe(' Forget Password Test', function () {
    beforeEach(function () {
        $this->user = User::factory()->create(['email' => 'new@example.com']);
    });

    it('sends a reset link email successfully', function () {
        // Mock the Password broker to avoid sending real emails
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => 'new@example.com'])
            ->andReturn(Password::RESET_LINK_SENT);

        // Act
        $response = actingAs($this->user)->postJson('/api/v1/auth/forget_password', [
            'email' => 'new@example.com',
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'error_code' => null,
                'message' => 'An Email with token sent successfully',
                'data' => null,
                'pagination' => null,

            ]);
    });

    it('fails to send reset link if email is invalid', function () {
        // Act
        $response = actingAs($this->user)->postJson('/api/v1/auth/forget_password', [
            'email' => 'invalidemail@example.com',
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    });
});

describe('Reset Password', function () {
    beforeEach(function () {
        $this->user = User::factory()->create([
            'email' => 'new2@example.com',
            'password' => Hash::make('oldpassword'),
        ]);
    });

    it('resets the password successfully', function () {
        // Mock the Password broker to reset the password successfully
        Password::shouldReceive('reset')
            ->once()
            ->withArgs(function ($credentials, $callback) {
                $callback($this->user, 'newpassword');

                return true;
            })
            ->andReturn(Password::PASSWORD_RESET);

        // Act
        $response = actingAs($this->user)->postJson('/api/v1/auth/reset_password', [
            'email' => 'new2@example.com',
            'token' => Str::random(60), // Normally, you'd have the real token
            'password' => 'Newp@ssword123',
            'password_confirmation' => 'Newp@ssword123',
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'error_code' => null,
                'message' => __('passwords.reset'),
                'data' => null,
                'pagination' => null,
            ]);

        // Ensure password has been updated in the database
        $this->assertTrue(Hash::check('newpassword', $this->user->fresh()->password));
    });

    it('fails to reset password with invalid token', function () {
        // Mock the Password broker to simulate invalid token
        Password::shouldReceive('reset')
            ->once()
            ->andReturn(Password::INVALID_TOKEN);

        // Act
        $response = actingAs($this->user)->postJson('/api/v1/auth/reset_password', [
            'email' => 'new2@example.com',
            'token' => 'invalidtoken',
            'password' => 'Newp@ssword123',
            'password_confirmation' => 'Newp@ssword123',
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'success' => false,
                'error_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => __('passwords.token'),
                'data' => null,
                'pagination' => null,
            ]);
    });
});
