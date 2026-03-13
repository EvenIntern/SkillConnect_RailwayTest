<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function show(string $path)
    {
        if (str_contains($path, '..')) {
            abort(404);
        }

        $disk = config('filesystems.media_disk', config('filesystems.default'));

        if (! Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        return Storage::disk($disk)->response($path, null, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
