# CONTINUITY.md

2026-03-09T13:55:00+07:00 [USER] Requested project-specific AGENTS.md and CONTINUITY.md based on the current SkillConnect repo and work completed in this chat.
2026-03-09T14:00:00+07:00 [TOOL] Confirmed workspace root is `C:\Users\LENOVO\Desktop\sunib\Projects\SkillConnectDEV\SkillConnect`; no existing `AGENTS.md` or `.agent/CONTINUITY.md` was present.
2026-03-09T14:01:00+07:00 [CODE] Added production-hardening changes earlier in this session: route protection, profile update cleanup, policy wiring fixes, PostgreSQL/Railway alignment, integrity constraints, and targeted tests.
2026-03-09T14:02:00+07:00 [CODE] Added fallback and reliability fixes earlier in this session: Alpine fallback in layout, project create route-order fix, full-page fallback for project creation, and media-serving route for profile assets.
2026-03-09T14:03:00+07:00 [TOOL] Latest local verification completed earlier in this session: `php artisan test` passed with 32 tests and 109 assertions.
2026-03-09T14:04:00+07:00 [ASSUMPTION] UNCONFIRMED: Railway production still needs redeploy with the newest local fixes unless the user has already pushed and redeployed after the latest code changes.
2026-03-09T15:10:00+07:00 [USER] Reported initial test-round bugs: own-project volunteer flow, profile media not displaying, follow interactions from followers/following modal, and 404 behavior tied to `/media/...` URLs.
2026-03-09T15:22:00+07:00 [CODE] Fixed interaction regressions: disabled owner volunteer actions in feed/discovery views, added safe redirect handling in follow/apply controllers for invalid `/media/...` previous URLs, and updated follower-list avatars to use real profile media URLs.
2026-03-09T15:24:00+07:00 [CODE] Added regression coverage in `tests/Feature/InteractionRegressionTest.php` for owner self-apply prevention and safe follow redirects.
2026-03-09T15:26:00+07:00 [TOOL] Full local verification completed: `php artisan test` passed with 34 tests and 117 assertions after the regression fixes.
2026-03-09T15:45:00+07:00 [USER] Reported that the site does not update live and requires manual refresh for likes, comments, pending projects, and saved projects.
2026-03-09T15:58:00+07:00 [CODE] Implemented lightweight live polling instead of websockets: added project stats endpoint, dashboard live endpoint, discovery live endpoint, dashboard/discovery partials, and frontend polling for feed metrics and sidebar sections.
2026-03-09T16:00:00+07:00 [CODE] Added endpoint coverage in `tests/Feature/LiveUpdateEndpointTest.php` for project stats, dashboard live panels, and discovery saved-project refresh.
2026-03-09T16:02:00+07:00 [TOOL] Full local verification completed: `php artisan test` passed with 37 tests and 128 assertions after the live-update implementation.
2026-03-09T16:25:00+07:00 [USER] Requested case-insensitive project title search plus more consistent login/register and dashboard hero styling.
2026-03-09T16:34:00+07:00 [CODE] Updated `App\Models\Project::scopeFilter()` to use a database-aware case-insensitive search operator and added guest-safe navigation fallbacks for public discovery access.
2026-03-09T16:36:00+07:00 [CODE] Refreshed auth/dashboard presentation in `resources/views/layouts/guest.blade.php`, `resources/views/auth/*.blade.php`, and `resources/views/home.blade.php` with self-contained branded styling.
2026-03-09T16:40:00+07:00 [CODE] Added `tests/Feature/SearchFilterTest.php` to cover lowercase search queries matching mixed-case project titles.
2026-03-09T16:41:00+07:00 [TOOL] Full local verification completed: `php artisan test` passed with 38 tests and 131 assertions after the search and styling changes.
2026-03-09T16:55:00+07:00 [USER] Reported that posting a project comment causes a full page refresh that strips CSS/styling.
2026-03-09T17:02:00+07:00 [CODE] Fixed project detail rendering split: `ProjectController::show()` now returns the modal partial only for AJAX requests and a new `projects/show-page.blade.php` wrapper for normal page loads.
2026-03-09T17:05:00+07:00 [CODE] Added regression coverage in `tests/Feature/MediaAndProjectCreateTest.php` for both full-page and AJAX project detail rendering.
2026-03-09T17:06:00+07:00 [TOOL] Full local verification completed: `php artisan test` passed with 40 tests and 137 assertions after the project detail fallback fix.
2026-03-09T17:35:55+07:00 [USER] Reported that clicking project comments opens a broken modal state with the loading overlay and duplicated page shell instead of the project detail partial.
2026-03-09T17:35:55+07:00 [CODE] Updated `resources/views/layouts/app.blade.php` so `openProjectModal()` sends the AJAX header Laravel expects, resets the spinner before each load, and falls back to the full project page if the fetch fails.
2026-03-09T17:35:55+07:00 [TOOL] Targeted verification completed: `php artisan test --filter=MediaAndProjectCreateTest` passed after the modal fetch fix.
2026-03-09T17:54:06+07:00 [USER] Reported that the page started printing part of the `openProjectModal()` JavaScript into the document after the previous modal fix.
2026-03-09T17:54:06+07:00 [CODE] Corrected the inline `x-data` quoting in `resources/views/layouts/app.blade.php` by switching the injected spinner markup to single-quoted HTML attributes so the body attribute is not terminated early.
2026-03-09T17:54:06+07:00 [TOOL] Re-verified with `php artisan view:clear` and `php artisan test --filter=MediaAndProjectCreateTest` after the quoting fix.
2026-03-09T18:06:37+07:00 [USER] Reported that profile pictures were not showing on the discovery page.
2026-03-09T18:06:37+07:00 [CODE] Updated `resources/views/discovery.blade.php` to render each project owner's `avatar_url` when present and fall back to the generated UI avatar only when no uploaded profile image exists.
2026-03-09T18:06:37+07:00 [CODE] Added a discovery avatar regression test in `tests/Feature/MediaAndProjectCreateTest.php` covering uploaded avatar rendering on the discover feed.
2026-03-09T18:06:37+07:00 [TOOL] Targeted verification completed: `php artisan test --filter=MediaAndProjectCreateTest` passed after the discovery avatar fix.
2026-03-09T18:07:57+07:00 [USER] Requested the same profile-picture fix for the messages pages.
2026-03-09T18:07:57+07:00 [CODE] Updated `resources/views/messages/_conversation-list.blade.php` and `resources/views/messages/show.blade.php` to use each participant's `avatar_url` with the generated avatar only as a fallback.
2026-03-09T18:07:57+07:00 [CODE] Extended `tests/Feature/MediaAndProjectCreateTest.php` with coverage for uploaded avatars on both the message list and selected conversation views.
2026-03-09T18:07:57+07:00 [TOOL] Targeted verification completed: `php artisan test --filter=MediaAndProjectCreateTest` passed after the messages avatar fix.
2026-03-13T21:28:36+07:00 [USER] Reported that uploaded profile pictures disappear after inactivity and requested a precise root-cause fix with validation.
2026-03-13T21:28:36+07:00 [CODE] Added a dedicated `filesystems.media_disk` config path, wired profile upload/read paths to it, and documented `MEDIA_DISK` in `.env.example` so Railway deployments can keep avatars/banners on persistent object storage instead of ephemeral local disk.
2026-03-13T21:28:36+07:00 [CODE] Strengthened media regression coverage so uploads target the dedicated media disk even when the app default disk differs, and local media reads resolve through the configured media disk.
2026-03-13T21:28:36+07:00 [TOOL] Verification completed: `php artisan test tests/Feature/DatabaseIntegrityTest.php`, `php artisan test tests/Feature/MediaAndProjectCreateTest.php`, and full `php artisan test` all passed (42 tests, 144 assertions).
2026-03-14T01:08:30+07:00 [USER] Shared S3-compatible storage credentials/environment values after choosing a non-AWS provider and needed the app to support private object storage.
2026-03-14T01:08:30+07:00 [CODE] Updated `App\Models\User::mediaUrl()` so avatars and banners always resolve through `route('media.show')`, allowing private S3-compatible/Railway-backed object storage to work without public bucket URLs.
2026-03-14T01:08:30+07:00 [CODE] Expanded media regression coverage to assert the dedicated media disk still stores uploads correctly and that S3-compatible media can be served through the application route.
2026-03-14T01:08:30+07:00 [TOOL] Verification completed: `php artisan test tests/Feature/DatabaseIntegrityTest.php`, `php artisan test tests/Feature/MediaAndProjectCreateTest.php`, and full `php artisan test` all passed (43 tests, 149 assertions).
