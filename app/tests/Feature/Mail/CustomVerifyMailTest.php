<?php

namespace Feature\Mail;

use App\Mail\CustomVerifyEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use ReflectionClass;
use Tests\TestCase;

class CustomVerifyMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_url_when_created_then_contains_correct_parameters()
    {
        $user = User::factory()->create();

        $expectedUrl = URL::temporarySignedRoute(
            'api.verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $customVerifyEmail = new CustomVerifyEmail;

        $reflection = new ReflectionClass($customVerifyEmail);
        $method = $reflection->getMethod('verificationUrl');
        $method->setAccessible(true);

        $verificationUrl = $method->invoke($customVerifyEmail, $user);

        $this->assertEquals($expectedUrl, $verificationUrl);
    }
}
