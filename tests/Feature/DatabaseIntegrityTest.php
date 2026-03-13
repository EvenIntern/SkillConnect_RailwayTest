<?php

use App\Models\Application;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('duplicate applications are blocked at the database layer', function () {
    $owner = User::factory()->create();
    $applicant = User::factory()->create();

    $project = Project::create([
        'user_id' => $owner->id,
        'title' => 'Neighborhood Food Drive',
        'description' => 'Coordinate food collection logistics.',
        'status' => 'Open',
        'schedule_details' => 'Saturday afternoon',
        'location_address' => 'Bandung',
        'volunteers_needed' => 8,
    ]);

    Application::create([
        'user_id' => $applicant->id,
        'project_id' => $project->id,
    ]);

    expect(fn () => Application::create([
        'user_id' => $applicant->id,
        'project_id' => $project->id,
    ]))->toThrow(QueryException::class);
});

test('profile media uses the dedicated media disk instead of the app default disk', function () {
    Storage::fake('public');
    Storage::fake('s3');
    config([
        'filesystems.default' => 'public',
        'filesystems.media_disk' => 's3',
    ]);

    $user = User::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'name' => 'Jane Doe',
    ]);

    $response = $this->actingAs($user)->patch(route('profile.update'), [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => $user->email,
        'avatar' => UploadedFile::fake()->create('avatar.jpg', 64, 'image/jpeg'),
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.show', $user));

    $user->refresh();

    expect($user->avatar_path)->not->toBeNull();
    expect($user->avatar_url)->toBe(route('media.show', ['path' => $user->avatar_path]));
    Storage::disk('s3')->assertExists($user->avatar_path);
    Storage::disk('public')->assertMissing($user->avatar_path);
});
