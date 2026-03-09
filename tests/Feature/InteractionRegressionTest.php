<?php

use App\Models\Project;
use App\Models\User;

test('project owners cannot apply to their own project and are redirected safely', function () {
    $owner = User::factory()->create();

    $project = Project::create([
        'user_id' => $owner->id,
        'title' => 'Neighborhood Cleanup',
        'description' => 'Organize volunteers for a cleanup event.',
        'status' => 'Community',
        'schedule_details' => 'Saturday morning',
        'location_address' => 'Jakarta',
        'volunteers_needed' => 5,
    ]);

    $this->actingAs($owner)
        ->from(route('media.show', ['path' => 'avatars/example.jpg']))
        ->post(route('projects.apply', $project))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('error', 'You cannot apply to your own project.');

    expect($project->applications()->count())->toBe(0);
});

test('following a user redirects back to the profile page when previous url is invalid media', function () {
    $follower = User::factory()->create();
    $profileUser = User::factory()->create();

    $this->actingAs($follower)
        ->from(route('media.show', ['path' => 'avatars/example.jpg']))
        ->post(route('users.follow', $profileUser))
        ->assertRedirect(route('profile.show', $profileUser))
        ->assertSessionHas('success', 'Follow status updated!');

    expect($follower->fresh()->following->contains($profileUser))->toBeTrue();
});
