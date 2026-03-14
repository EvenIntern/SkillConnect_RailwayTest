<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->withoutVite();
});

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.show', $user));

    $user->refresh();

    $this->assertSame('Test', $user->first_name);
    $this->assertSame('User', $user->last_name);
    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'first_name' => $user->first_name ?? 'Test',
            'last_name' => $user->last_name ?? 'User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.show', $user));

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('profile media upload failures are returned as validation errors instead of a server error', function () {
    Storage::fake('public');

    $existingPath = UploadedFile::fake()->create('existing-avatar.jpg', 64, 'image/jpeg')->store('avatars', 'public');

    $user = User::factory()->create([
        'avatar_path' => $existingPath,
    ]);

    config(['filesystems.media_disk' => 'missing-media-disk']);

    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->patch(route('profile.update'), [
            'first_name' => $user->first_name ?? 'Test',
            'last_name' => $user->last_name ?? 'User',
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->create('new-avatar.jpg', 64, 'image/jpeg'),
        ]);

    $response
        ->assertSessionHasErrors('avatar')
        ->assertRedirect(route('profile.edit'));

    $user->refresh();

    expect($user->avatar_path)->toBe($existingPath);
    Storage::disk('public')->assertExists($existingPath);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});
