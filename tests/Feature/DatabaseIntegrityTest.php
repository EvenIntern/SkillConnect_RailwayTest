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

test('filesystem config accepts provider-specific s3 environment variable aliases', function () {
    $originalEnv = [];
    $setEnv = function (string $key, ?string $value): void {
        if ($value === null) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);

            return;
        }

        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    };

    foreach ([
        'AWS_BUCKET',
        'AWS_S3_BUCKET_NAME',
        'AWS_ENDPOINT',
        'AWS_ENDPOINT_URL',
        'AWS_S3_URL_STYLE',
        'AWS_USE_PATH_STYLE_ENDPOINT',
        'AWS_ACCESS_KEY_ID',
        'AWS_SECRET_ACCESS_KEY',
        'MEDIA_DISK',
        'FILESYSTEM_DISK',
    ] as $key) {
        $originalEnv[$key] = getenv($key);
    }

    $setEnv('AWS_BUCKET', null);
    $setEnv('AWS_ENDPOINT', null);
    $setEnv('AWS_USE_PATH_STYLE_ENDPOINT', null);
    $setEnv('MEDIA_DISK', null);
    $setEnv('FILESYSTEM_DISK', 'public');
    $setEnv('AWS_S3_BUCKET_NAME', 'skillconnect-media-zqaqlq');
    $setEnv('AWS_ENDPOINT_URL', 'https://t3.storageapi.dev');
    $setEnv('AWS_S3_URL_STYLE', 'virtual-host');
    $setEnv('AWS_ACCESS_KEY_ID', 'test-key');
    $setEnv('AWS_SECRET_ACCESS_KEY', 'test-secret');

    try {
        $config = require base_path('config/filesystems.php');

        expect($config['media_disk'])->toBe('s3');
        expect($config['disks']['s3']['bucket'])->toBe('skillconnect-media-zqaqlq');
        expect($config['disks']['s3']['endpoint'])->toBe('https://t3.storageapi.dev');
        expect($config['disks']['s3']['use_path_style_endpoint'])->toBeFalse();
    } finally {
        foreach ($originalEnv as $key => $value) {
            $setEnv($key, $value === false ? null : $value);
        }
    }
});
