<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function halaman_login_dapat_diakses()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_dapat_login_dengan_kredensial_yang_benar()
    {
        $user = User::factory()->create([
            'email' => 'admin@polrestabes.go.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@polrestabes.go.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_tidak_dapat_login_dengan_kredensial_yang_salah()
    {
        User::factory()->create([
            'email' => 'admin@polrestabes.go.id',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@polrestabes.go.id',
            'password' => 'password_salah',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_dapat_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function halaman_auth_google_mengarahkan_ke_mock_chooser_jika_env_kosong()
    {
        config(['services.google.client_id' => '']);
        config(['services.google.client_secret' => '']);

        $response = $this->get(route('auth.google'));
        $response->assertRedirect(route('auth.google.mock'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function halaman_mock_chooser_dapat_diakses()
    {
        $response = $this->get(route('auth.google.mock'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.google-mock');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function handle_mock_login_menyimpan_user_dan_log_in()
    {
        $response = $this->post(route('auth.google.mock-login'), [
            'name' => 'Mock Google User',
            'email' => 'mock.google@gmail.com',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'mock.google@gmail.com']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function handle_callback_google_menyimpan_user_dari_socialite_dan_log_in()
    {
        config(['services.google.client_id' => 'mock-client-id']);
        config(['services.google.client_secret' => 'mock-client-secret']);

        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-id-12345');
        $abstractUser->shouldReceive('getName')->andReturn('Google User');
        $abstractUser->shouldReceive('getEmail')->andReturn('google.user@gmail.com');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get('/auth/google/callback');
        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'google.user@gmail.com']);
    }
}
