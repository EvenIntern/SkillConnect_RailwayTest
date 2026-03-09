<?php

use App\Models\Application;
use App\Models\Comment;
use App\Models\Project;
use App\Models\User;

test('project stats endpoint returns live interaction state for visible projects', function () {
    $owner = User::factory()->create();
    $viewer = User::factory()->create();

    $project = Project::create([
        'user_id' => $owner->id,
        'title' => 'Mentor New Volunteers',
        'description' => 'Help onboard new community members.',
        'status' => 'Community',
        'schedule_details' => 'Weeknights',
        'location_address' => 'Remote',
        'volunteers_needed' => 3,
    ]);

    Comment::create([
        'project_id' => $project->id,
        'user_id' => $owner->id,
        'content' => 'Looking for motivated volunteers.',
    ]);

    $viewer->likedProjects()->attach($project->id);
    $viewer->savedProjects()->attach($project->id);

    Application::create([
        'project_id' => $project->id,
        'user_id' => $viewer->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($viewer)
        ->postJson(route('projects.stats'), [
            'project_ids' => [$project->id],
        ]);

    $response->assertOk();
    $response->assertJsonPath("projects.{$project->id}.likes_count", 1);
    $response->assertJsonPath("projects.{$project->id}.comments_count", 1);
    $response->assertJsonPath("projects.{$project->id}.has_applied", true);
    $response->assertJsonPath("projects.{$project->id}.is_saved", true);
    $response->assertJsonPath("projects.{$project->id}.is_owner", false);
});

test('dashboard live endpoint returns refreshed sidebar html', function () {
    $owner = User::factory()->create();
    $applicant = User::factory()->create();

    $project = Project::create([
        'user_id' => $owner->id,
        'title' => 'Community Garden',
        'description' => 'Maintain the neighborhood garden.',
        'status' => 'Environmental',
        'schedule_details' => 'Saturday morning',
        'location_address' => 'Jakarta',
        'volunteers_needed' => 5,
    ]);

    Application::create([
        'project_id' => $project->id,
        'user_id' => $applicant->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($owner)->getJson(route('dashboard.live'));

    $response->assertOk();
    expect($response->json('incoming_applicants_html'))->toContain('Community Garden');
    expect($response->json('incoming_applicants_html'))->toContain($applicant->name);
});

test('discover live endpoint returns refreshed saved projects html', function () {
    $owner = User::factory()->create();
    $viewer = User::factory()->create();

    $project = Project::create([
        'user_id' => $owner->id,
        'title' => 'Food Drive Logistics',
        'description' => 'Coordinate delivery schedules.',
        'status' => 'Community',
        'schedule_details' => 'Flexible',
        'location_address' => 'Bandung',
        'volunteers_needed' => 4,
    ]);

    $viewer->savedProjects()->attach($project->id);

    $response = $this->actingAs($viewer)->getJson(route('discover.live'));

    $response->assertOk();
    expect($response->json('saved_projects_html'))->toContain('Food Drive Logistics');
});
