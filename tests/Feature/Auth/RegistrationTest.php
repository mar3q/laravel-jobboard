<?php

declare(strict_types=1);

use App\Models\User;

test('registration screen can be rendered', function () {
    $this->get('/register')->assertStatus(200);
});

test('candidate can register and gets role assigned', function () {
    $response = $this->post('/register', [
        'name' => 'Jan Kowalski',
        'email' => 'jan@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'candidate',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $user = User::where('email', 'jan@example.com')->firstOrFail();
    expect($user->hasRole('candidate'))->toBeTrue();
});

test('employer can register and gets role assigned', function () {
    $this->post('/register', [
        'name' => 'Anna Nowak',
        'email' => 'anna@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'employer',
    ]);

    $user = User::where('email', 'anna@example.com')->firstOrFail();
    expect($user->hasRole('employer'))->toBeTrue();
});

test('admin role cannot be self-assigned via registration', function () {
    $response = $this->post('/register', [
        'name' => 'X',
        'email' => 'x@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'admin',
    ]);

    $response->assertSessionHasErrors('role');
});
