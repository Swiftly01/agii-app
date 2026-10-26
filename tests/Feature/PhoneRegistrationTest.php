<?php

namespace Tests\Feature;

use App\Contracts\SmsGateway;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PhoneRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private object $sms;

    protected function setUp(): void
    {
        parent::setUp();

        // Captures outgoing messages instead of calling a real provider
        $this->sms = new class implements SmsGateway
        {
            public array $sent = [];

            public function send(string $to, string $body): void
            {
                $this->sent[] = ['to' => $to, 'body' => $body];
            }
        };

        $this->app->instance(SmsGateway::class, $this->sms);
        config(['queue.default' => 'sync']);
    }

    private function register(array $overrides = []): void
    {
        $this->post(route('phone.register.store'), array_merge([
            'first_name' => 'Ada',
            'last_name' => 'Obi',
            'phone' => '0803 123 4567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'customer',
            'agree_terms' => '1',
        ], $overrides));
    }

    private function sentCode(): string
    {
        preg_match('/\b(\d{6})\b/', end($this->sms->sent)['body'], $matches);

        return $matches[1];
    }

    public function test_signup_creates_unverified_user_and_sends_code(): void
    {
        $this->register();

        $user = User::where('phone', '+2348031234567')->firstOrFail();

        $this->assertNull($user->phone_verified_at);
        $this->assertNull($user->email);
        $this->assertCount(1, $this->sms->sent);
        $this->assertSame('+2348031234567', $this->sms->sent[0]['to']);
        $this->assertAuthenticatedAs($user);
    }

    public function test_correct_code_verifies_the_phone(): void
    {
        $this->register();

        $this->post(route('phone.verification.verify'), ['code' => $this->sentCode()])->assertRedirect();

        $this->assertNotNull(User::first()->phone_verified_at);
    }

    public function test_wrong_code_is_rejected(): void
    {
        $this->register();

        $this->post(route('phone.verification.verify'), ['code' => '000000'])->assertSessionHasErrors('code');

        $this->assertNull(User::first()->phone_verified_at);
    }

    public function test_resend_is_blocked_during_cooldown(): void
    {
        $this->register();

        $this->post(route('phone.verification.resend'))->assertSessionHasErrors('code');

        $this->assertCount(1, $this->sms->sent);
    }

    public function test_unverified_phone_user_cannot_leave_the_verification_flow(): void
    {
        $this->register();

        $this->get('/home')->assertRedirect(route('phone.verification.notice'));
    }

    public function test_duplicate_phone_numbers_are_rejected_across_formats(): void
    {
        $this->register();
        auth()->logout();

        $this->register(['phone' => '+234 803 123 4567']);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_can_log_in_with_phone_number(): void
    {
        User::create([
            'first_name' => 'Ada',
            'last_name' => 'Obi',
            'email' => null,
            'phone' => '+2348031234567',
            'phone_verified_at' => now(),
            'password' => 'password123',
            'user_type' => 'customer',
        ]);

        $this->post(route('login.post'), ['email' => '08031234567', 'password' => 'password123']);

        $this->assertAuthenticated();
    }
}
