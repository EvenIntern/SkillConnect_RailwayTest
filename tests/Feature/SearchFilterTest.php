<?php

use App\Models\Project;
use App\Models\User;

test('discover search matches project titles without case sensitivity', function () {
    $owner = User::factory()->create();

    Project::create([
        'user_id' => $owner->id,
        'title' => 'Project Development',
        'description' => 'Build a volunteer coordination app.',
        'status' => 'Programming',
        'schedule_details' => 'Weekdays',
        'location_address' => 'Remote',
        'volunteers_needed' => 4,
    ]);

    Project::create([
        'user_id' => $owner->id,
        'title' => 'Community Garden',
        'description' => 'Maintain the neighborhood garden.',
        'status' => 'Environmental',
        'schedule_details' => 'Weekends',
        'location_address' => 'Jakarta',
        'volunteers_needed' => 3,
    ]);

    $this->get(route('discover', ['search' => 'project development']))
        ->assertOk()
        ->assertSee('Project Development')
        ->assertDontSee('Community Garden');
});
