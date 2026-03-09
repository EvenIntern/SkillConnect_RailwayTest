<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
            .gradient-bg {
                background: linear-gradient(135deg, #2563eb, #10b981);
            }

            .auth-shell {
                background-image:
                    radial-gradient(circle at top left, rgba(255, 255, 255, 0.22), transparent 35%),
                    radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.22), transparent 30%),
                    linear-gradient(135deg, #1d4ed8, #0f766e);
            }

            .auth-card {
                box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.28);
            }

            .auth-panel-label {
                letter-spacing: 0.3em;
            }

            .auth-pill {
                background: rgba(255, 255, 255, 0.14);
                border: 1px solid rgba(255, 255, 255, 0.22);
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="auth-shell min-h-screen flex flex-col justify-center items-center px-4 py-8">
            <div class="w-full max-w-5xl grid gap-8 lg:grid-cols-[1.1fr,0.9fr] items-center">
                <div class="hidden lg:block text-white">
                    <p class="auth-panel-label text-xs font-semibold uppercase text-blue-100">Volunteer Network</p>
                    <h1 class="mt-4 text-5xl font-bold leading-tight">Connect skilled volunteers with projects that need them now.</h1>
                    <p class="mt-5 max-w-xl text-lg text-blue-50">
                        SkillConnect helps students, professionals, and organizers discover opportunities, collaborate, and build real project experience.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3 text-sm font-medium">
                        <span class="auth-pill rounded-full px-4 py-2">Discover opportunities</span>
                        <span class="auth-pill rounded-full px-4 py-2">Post community needs</span>
                        <span class="auth-pill rounded-full px-4 py-2">Message collaborators</span>
                    </div>
                </div>

                <div>
                    <div class="text-center lg:text-left">
                        <a href="/" class="inline-flex items-center gap-3 text-white">
                            <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 text-3xl backdrop-blur">
                                <i class="fas fa-hands-helping"></i>
                            </span>
                            <span>
                                <span class="block text-3xl font-bold">SkillConnect</span>
                                <span class="block text-sm text-blue-100">Build experience through community work</span>
                            </span>
                        </a>
                    </div>

                    <div class="auth-card w-full mt-6 px-6 py-6 bg-white/95 shadow-md overflow-hidden sm:rounded-3xl border border-white/40 backdrop-blur sm:px-8 sm:py-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
