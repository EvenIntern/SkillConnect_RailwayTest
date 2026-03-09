# AGENTS.md

Project-specific guidance for Codex and other coding agents. Read this file before making changes.
If instructions here conflict with system/developer rules, system/developer rules win. If conflicts with other repo docs, call it out explicitly.

## Project Summary
- Repo for: `SkillConnect`, a volunteer/project matching social platform
- Backend: Laravel 11 monolith on PHP 8.2
- Frontend: Blade views, Vite, Tailwind CSS, Alpine.js
- Database: PostgreSQL
- Deployment direction:
  - Near term: Railway monolith deployment
  - Later: frontend on Vercel, backend/API + PostgreSQL on Railway
- Core features:
  - auth
  - profiles with skills/experience
  - project discovery and creation
  - applications
  - comments
  - follows
  - direct messaging

## Mission
Keep this project accurate, maintainable, safe, and production-oriented while preserving existing Laravel conventions and the current product direction.

## Baseline Workflow
- Establish the requested outcome and acceptance criteria.
- Inspect the relevant controller, model, route, migration, view, and config files before changing behavior.
- Check whether the change affects production deployment, database integrity, auth, storage, or frontend interactions.
- Prefer targeted fixes over architectural rewrites unless the user explicitly asks for broader refactoring.
- If requirements are ambiguous and the change is risky or irreversible, ask a narrow clarifying question first.

## Accuracy, Recency, and Sourcing (Required)
When a request depends on recency ("latest", "current", "today", "as of now"):
- State the current date/time in ISO format.
- Prefer official or primary sources for framework, platform, deployment, and API guidance.
- Prefer the latest authoritative docs, release notes, and changelogs.
- Cross-check at least two reputable sources when deployment, security, or compatibility is sensitive.

### Web Search
- Use web search when it materially improves correctness or when required by higher-priority instructions.
- Prefer official sources:
  - Laravel docs
  - Railway docs
  - Vite docs
  - Tailwind docs
  - Alpine docs
- Record source dates when relevant.

## Environment And Deployment Policy
- Do not assume Docker exists for this repo. There is currently no container workflow in-repo.
- Prefer the existing local Laravel workflow unless the user explicitly wants Docker added.
- Treat Railway as the active deployment target unless the user changes direction.
- Avoid recommending host-only package installs unless required for the task.

## Repo Conventions
- Run commands from repo root: `C:\Users\LENOVO\Desktop\sunib\Projects\SkillConnectDEV\SkillConnect`
- Use `rg` for searches.
- Avoid destructive git commands.
- Keep new files ASCII unless the file already uses Unicode.
- Preserve Laravel file organization and naming conventions.

## Environments And Commands
- Local PHP app:
  - `php artisan serve`
- Local frontend assets:
  - `npm install`
  - `npm run dev`
  - `npm run build`
- Local dependencies:
  - `composer install`
- Database:
  - PostgreSQL is the primary database
- Tests:
  - `php artisan test`
  - Run targeted tests for touched areas when possible
- Useful maintenance:
  - `php artisan migrate`
  - `php artisan optimize:clear`
  - `php artisan storage:link`

## Backend Standards
- Language/runtime: PHP 8.2 + Laravel 11
- Keep configuration in `.env` and `.env.example`
- Prefer Eloquent relationships, policies, validation, and conventional controllers over ad hoc patterns
- Enforce important business rules at the database layer where possible
- Keep timestamps in UTC and ISO-8601 where applicable
- If adding a dependency, update `composer.json` and lockfile as needed
- Be careful with route ordering; dynamic routes must not shadow static routes

## Frontend Standards
- App structure: Blade-rendered server app with Alpine-enhanced interactions
- Current UI stack:
  - Blade layouts/components
  - Tailwind utilities
  - Vite asset pipeline
  - Alpine for modals, dropdowns, and small interactions
- Preserve existing design language unless the user asks for a redesign
- Prefer progressive enhancement:
  - interactive UI should degrade gracefully when JavaScript fails
- If adding a dependency, update `package.json` and lockfile

## Production And Infra Notes
- Production target is Railway
- Production database is PostgreSQL
- `APP_DEBUG` must stay `false` in production unless debugging temporarily
- Current production-safe temporary settings may include:
  - `SESSION_DRIVER=file`
  - `CACHE_STORE=file`
  - `QUEUE_CONNECTION=sync`
- Do not assume `public/storage` symlinks are reliable on Railway; storage behavior must be verified
- Uploaded media should remain accessible after redeploys
- Do not expose or commit secrets, keys, or live database URLs

## Data and Privacy
- Do not print or commit secrets, tokens, keys, or full production connection strings
- If a secret is pasted into chat or terminal output, advise rotation
- Avoid commands that dump all env vars or sensitive config
- Do not modify production data unless explicitly asked and clearly confirmed

## Editing Files
- Make the smallest safe change that solves the issue
- Preserve established Laravel, Blade, and Tailwind patterns
- Prefer patch-style edits with reviewable diffs
- Default to read-only exploration before changes
- For deployment changes, prefer safe fallbacks over fragile assumptions

## Reading Project Documents
- Read the full document before changing instructions derived from it
- Re-read relevant code or docs before finalizing conclusions
- If paraphrasing project behavior or deployment guidance, label it as paraphrase where needed

## CONTINUITY.md (Required)
Maintain a single continuity file for this workspace: `.agent/CONTINUITY.md`.
- At the start of each assistant turn, read `.agent/CONTINUITY.md` if it exists.
- Update only when there is a meaningful delta in plans, decisions, progress, discoveries, or outcomes.
- Every entry must include an ISO timestamp and a provenance tag: `[USER]`, `[CODE]`, `[TOOL]`, `[ASSUMPTION]`.
- Use `UNCONFIRMED` when necessary; do not guess.
- Keep it short and high-signal.

## Tests and Validation
- Add targeted tests when feasible.
- Run relevant checks after meaningful changes:
  - `php artisan test`
  - focused test files when appropriate
  - `npm run build` for asset-sensitive changes
- If tests or build are not run, state that explicitly.

## Definition of Done
A task is done when:
- The requested change is implemented or the question is answered.
- Verification is provided or explicitly skipped with reason.
- Documentation is updated when the change affects usage, deployment, or project conventions.
- Impact is explained briefly: what changed, where, and why.
- Follow-ups are listed if anything is intentionally deferred.
- `.agent/CONTINUITY.md` is updated if the task materially changes project state, decisions, or next steps.

## Deliverables From Codex
- Provide a short summary of changes.
- List files changed.
- List commands run, or note if none.
