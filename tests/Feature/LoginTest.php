<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
