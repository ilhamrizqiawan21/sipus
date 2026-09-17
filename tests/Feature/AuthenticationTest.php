<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_active_user_can_login_and_reach_dashboard(): void
    {
        $user = User::factory()->create([
            'username' => 'guru.sipus',
            'password' => 'password',
            'role' => 'guru',
        ]);

        $response = $this->post('/login', [
            'username' => 'guru.sipus',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'login',
        ]);
        $this->get('/dashboard')->assertOk();
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->create([
            'username' => 'inactive.user',
            'password' => 'password',
            'status' => false,
        ]);

        $response = $this->from('/login')->post('/login', [
            'username' => 'inactive.user',
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_only_admin_can_access_admin_route(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $this->actingAs($user)->get('/admin')->assertForbidden();

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/admin')->assertRedirect(route('dashboard'));
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create(['username' => 'old.username']);

        $response = $this->actingAs($user)->patch('/profil', [
            'nama' => 'Nama Baru',
            'username' => 'new.username',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nama' => 'Nama Baru',
            'username' => 'new.username',
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'profile_updated',
        ]);
    }

    public function test_admin_password_can_be_reset_from_console(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin.sipus',
            'role' => 'admin',
        ]);

        $this->artisan('sipus:reset-admin-password', [
            'username' => 'admin.sipus',
            '--password' => 'new-password',
        ])->assertExitCode(0);

        $this->assertTrue(Hash::check('new-password', $admin->fresh()->password));
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'admin_password_reset',
        ]);
    }
}
