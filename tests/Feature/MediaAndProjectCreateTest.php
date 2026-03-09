<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('local profile media is served through the application route', function () {
    Storage::fake('public');
    config(['filesystems.default' => 'public']);

    $path = UploadedFile::fake()->create('avatar.jpg', 64, 'image/jpeg')->store('avatars', 'public');

    $user = User::factory()->create([
        'avatar_path' => $path,
    ]);

    expect($user->avatar_url)->toBe(route('media.show', ['path' => $path]));

    $response = $this->get($user->avatar_url)
        ->assertOk();

    expect($response->headers->get('cache-control'))->toContain('public');
    expect($response->headers->get('cache-control'))->toContain('max-age=86400');
});

test('project create page gracefully falls back to a full page request', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('projects.create'))
        ->assertOk()
        ->assertSee('Create New Volunteer Opportunity');
});

test('project create modal can still fetch the partial form over ajax', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('projects.create'), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertSee('Post Opportunity')
        ->assertDontSee('<x-app-layout>', false);
});
