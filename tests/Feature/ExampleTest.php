<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

test('guests are redirected from the dashboard to login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect(route('login'));
});

test('authenticated users without dashboard permission are forbidden', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertForbidden();
});

test('users with dashboard permission can view the dashboard', function () {
    $permission = Permission::findOrCreate('dashboard.view', 'web');

    $user = User::factory()->create();
    $user->givePermissionTo($permission);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertSee('Dashboard');
});
