<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the assignment description on the homepage', function (): void {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('Тестовое задание')
        ->assertSee('/admin')
        ->assertSee('/api/v1')
        ->assertSee('/api/documentation')
        ->assertSee('Bearer-токеном');
});

it('renders filament login page', function (): void {
    $this->get('/admin/login')
        ->assertOk();
});

it('renders swagger documentation page', function (): void {
    $this->get('/api/documentation')
        ->assertOk();
});

it('creates admin user when seeding database', function (): void {
    $this->seed();

    expect(
        User::where('email', 'admin@magical.local')->exists()
    )->toBeTrue();
});
