<?php

namespace Tests\Feature\Http;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Mail\CustomVerifyEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_when_valid_data_then_account_created_and_notification_sent()
    {
        Notification::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);

        Notification::assertSentTo(
            User::where('email', 'test@example.com')->first(),
            CustomVerifyEmail::class
        );

        $this->assertCount(1, User::all());
    }

    public function test_verify_when_valid_link_then_email_verified()
    {
        $user = User::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            name: 'api.verification.verify',
            expiration: Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            parameters: [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ],
        );
        $response = $this->getJson($url);

        $response->assertStatus(200);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_verify_when_invalid_link_then_error_returned()
    {
        $user = User::factory()->unverified()->create();

        // Create an invalid URL by modifying the hash
        $invalidUrl = URL::temporarySignedRoute(
            name: 'api.verification.verify',
            expiration: Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            parameters: [
                'id' => $user->id,
                'hash' => sha1('invalid-email@example.com'),
            ],
        );

        $response = $this->getJson($invalidUrl)->assertStatus(400);
    }

    public function test_login_when_valid_credentials_then_token_returned()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_logout_when_authenticated_then_successfully_logged_out()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);
    }
}
