# Laravel Job Board

Portfolio project — a job board built with Laravel 11 + Blade + Tailwind.

Three roles: candidate, employer, admin. Candidates apply for jobs, employers manage offers and applications, admin moderates and sees stats.

## Stack

- PHP 8.3+, Laravel 11
- Blade + Tailwind + Alpine.js (Breeze)
- MySQL 8, Redis (cache, queue, session)
- Sanctum (API tokens), Horizon (queue UI)
- spatie/laravel-permission, spatie/laravel-activitylog
- Pest 3, Larastan, Laravel Pint
- Laravel Sail (Docker)

## Features

- Job listing with filters (city, seniority, contract, remote, salary, tags) and full-text search
- Application flow with CV upload, signed download URLs, queued email + database notifications
- Employer panel: CRUD jobs, review applications, change status with history
- Admin panel: dashboard with stats, job moderation, activity log
- Public API v1 (Sanctum) with token abilities, problem+json errors (RFC 7807)
- Policies for Job / Application / Company
- SEO: OG tags + JSON-LD `JobPosting`

## Quick start

```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
```

App: http://localhost
Mailpit: http://localhost:8025

## Demo accounts

```
admin@jobboard.test      / password
employer@jobboard.test   / password
candidate@jobboard.test  / password
```

## Tests

```bash
./vendor/bin/sail test
```

Uses SQLite in-memory — no MySQL required.

## API

```bash
# Get a token
curl -X POST http://localhost/api/v1/tokens \
  -H "Content-Type: application/json" \
  -d '{"email":"employer@jobboard.test","password":"password","device_name":"cli"}'

# List jobs
curl http://localhost/api/v1/jobs

# Create a job (employer token, ability: jobs:write)
curl -X POST http://localhost/api/v1/jobs \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{"company_id":1,"title":"Senior PHP","description":"...","seniority":"senior","contract_type":"b2b","status":"published","location_country":"PL"}'
```
