<?php

use App\Http\Controllers\MediaController;
use App\Models\Project;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('local profile media is served through the application route for the dedicated media disk', function () {
    Storage::fake('public');
    config([
        'filesystems.default' => 'local',
        'filesystems.media_disk' => 'public',
    ]);

    $path = UploadedFile::fake()->create('avatar.jpg', 64, 'image/jpeg')->store('avatars', 'public');

    $user = User::factory()->create([
        'avatar_path' => $path,
    ]);

    expect($user->avatar_url)->toBe(route('media.show', ['path' => $path]));

    $response = app(MediaController::class)->show($path);

    expect($response->getStatusCode())->toBe(200);
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

test('project details page gracefully falls back to a full page request', function () {
    $user = User::factory()->create();

    $project = Project::create([
        'user_id' => $user->id,
        'title' => 'Project Detail Fallback',
        'description' => 'Verify the project detail page renders inside the app layout.',
        'status' => 'Community',
        'schedule_details' => 'Flexible',
        'location_address' => 'Remote',
        'volunteers_needed' => 2,
    ]);

    $this->actingAs($user)
        ->get(route('projects.show', $project))
        ->assertOk()
        ->assertSee('Project Detail Fallback')
        ->assertSee('SkillConnect');
});

test('project details modal can still fetch the partial view over ajax', function () {
    $user = User::factory()->create();

    $project = Project::create([
        'user_id' => $user->id,
        'title' => 'Project Modal Partial',
        'description' => 'Verify the project modal remains a partial view.',
        'status' => 'Community',
        'schedule_details' => 'Flexible',
        'location_address' => 'Remote',
        'volunteers_needed' => 2,
    ]);

    $this->actingAs($user)
        ->get(route('projects.show', $project), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertSee('Project Modal Partial')
        ->assertDontSee('<x-app-layout>', false);
});

test('discovery page shows uploaded profile avatars for project owners', function () {
    Storage::fake('public');
    config(['filesystems.default' => 'public']);

    $path = UploadedFile::fake()->create('discover-avatar.jpg', 64, 'image/jpeg')->store('avatars', 'public');

    $user = User::factory()->create([
        'avatar_path' => $path,
    ]);

    Project::create([
        'user_id' => $user->id,
        'title' => 'Discovery Avatar Project',
        'description' => 'Verify the discovery page renders the stored avatar URL.',
        'status' => 'Community',
        'schedule_details' => 'Weekends',
        'location_address' => 'Jakarta',
        'volunteers_needed' => 4,
    ]);

    $this->get(route('discover'))
        ->assertOk()
        ->assertSee($user->avatar_url, false);
});

test('messages pages show uploaded avatars for the other participant', function () {
    Storage::fake('public');
    config(['filesystems.default' => 'public']);

    $path = UploadedFile::fake()->create('message-avatar.jpg', 64, 'image/jpeg')->store('avatars', 'public');

    $currentUser = User::factory()->create();
    $otherUser = User::factory()->create([
        'avatar_path' => $path,
    ]);

    $conversation = Conversation::create();
    $conversation->participants()->attach([$currentUser->id, $otherUser->id]);

    Message::create([
        'conversation_id' => $conversation->id,
        'user_id' => $otherUser->id,
        'body' => 'Hello from the conversation test.',
    ]);

    $this->actingAs($currentUser)
        ->get(route('messages.index'))
        ->assertOk()
        ->assertSee($otherUser->avatar_url, false);

    $this->actingAs($currentUser)
        ->get(route('messages.show', $conversation))
        ->assertOk()
        ->assertSee($otherUser->avatar_url, false);
});
