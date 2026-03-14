<?php

$mediaBucket = env('AWS_BUCKET', env('AWS_S3_BUCKET_NAME'));
$mediaEndpoint = env('AWS_ENDPOINT', env('AWS_ENDPOINT_URL'));
$mediaUrlStyle = env('AWS_S3_URL_STYLE');
$usePathStyleEndpoint = env(
    'AWS_USE_PATH_STYLE_ENDPOINT',
    $mediaUrlStyle ? $mediaUrlStyle === 'path' : false
);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Media Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Profile avatars and banners need persistent storage in production. When
    | S3-compatible credentials are available, prefer that disk automatically
    | unless MEDIA_DISK is explicitly overridden.
    |
    */

    'media_disk' => env(
        'MEDIA_DISK',
        env('AWS_ACCESS_KEY_ID') && env('AWS_SECRET_ACCESS_KEY') && $mediaBucket
            ? 's3'
            : env('FILESYSTEM_DISK', 'public')
    ),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => $mediaBucket,
            'url' => env('AWS_URL'),
            'endpoint' => $mediaEndpoint,
            'use_path_style_endpoint' => $usePathStyleEndpoint,
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
