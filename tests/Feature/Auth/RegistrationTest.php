<?php

test('public registration page is not available', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
});

test('public registration submissions are not accepted', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertNotFound();
});
