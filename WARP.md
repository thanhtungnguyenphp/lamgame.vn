# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Quickstart Commands

- Project context: Laravel 11 + Bagisto packages, PHP 8.2, Vite for assets, MySQL + Redis via Docker Compose.
- Preferred workflow is Docker-based. Some helper scripts assume a service named `app`, but docker-compose defines `php`. Use the docker-compose commands below if helpers fail.

### Docker lifecycle

```bash path=null start=null
# Bring up core services (php, nginx, mysql, redis, vite)
docker-compose up -d

# Stop everything
docker-compose down

# Show status
docker-compose ps

# Tail web server logs (nginx)
docker-compose logs -f nginx

# Shell into PHP container (use this for Artisan/Composer)
docker-compose exec php bash
```

Notes
- The Compose file requires an external network `traefik-public` and a build context `../emsaigon/docker/php`. If those don’t exist locally, either create them or adjust docker-compose.yml before running.

### Helper make targets (when scripts align with your environment)

```bash path=null start=null
# Discover available targets
make help

# Start/stop
make start        # up -d
make stop         # down
make restart
make logs         # app logs (script expects service 'app')
make shell        # container shell (script expects service 'app')

# Framework helpers
make artisan cmd="migrate"
make composer cmd="install"
make npm cmd="install"

# Database
make migrate
make seed
make fresh        # migrate:fresh --seed

# Cache/optimize
make cache-clear
make optimize
```

Caveat
- scripts/dev.sh uses `docker-compose exec app ...` but docker-compose.yml defines `php`. Prefer the raw docker-compose commands shown above if any make target fails.

### PHP dependencies

```bash path=null start=null
# Inside PHP container
composer install
composer dump-autoload
```

### Assets (Vite)

```bash path=null start=null
# Dev server
npm run dev

# Production build
npm run build
```

The compose service `vite` can also run the dev server (HMR on 5174).

### Lint/format (PHP)

```bash path=null start=null
# Laravel Pint (configured via pint.json)
./vendor/bin/pint -v

# Fix in-place
./vendor/bin/pint
```

### Tests

PHPUnit is configured (phpunit.xml) with multiple test suites targeting packages under packages/Webkul/*.

```bash path=null start=null
# Run all tests (Laravel test runner)
php artisan test

# Run all tests (PHPUnit directly)
./vendor/bin/phpunit

# Run a single test class by filter (PHPUnit)
./vendor/bin/phpunit --filter ClassNameTest

# Run tests in one file
./vendor/bin/phpunit packages/Webkul/Core/tests/Unit/SomeTest.php

# Run by testsuite name (from phpunit.xml)
./vendor/bin/phpunit --testsuite "Admin Feature Test"
```

If Pest is preferred (included in require-dev), you may also:

```bash path=null start=null
./vendor/bin/pest
./vendor/bin/pest -t "partial test name"
```

### Common Artisan

```bash path=null start=null
php artisan migrate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Environment and URLs

- Local URLs (from setup docs and compose):
  - App via Traefik: https://lamgame.localhost (HTTP auto-redirects to HTTPS per labels)
  - Nginx container exposes port 80 to Traefik service
  - Vite HMR: http://localhost:5174
  - Mailpit UI: http://localhost:8028
  - MySQL: localhost:33069 (container `lg-mysql`)
  - Redis: localhost:63794 (container `lg-redis`)

Adjust .env to match your local compose service names (DB host is usually the service name inside the network, e.g., DB_HOST=mysql; from host machine use forwarded ports).

## High-level architecture

This codebase is a Laravel 11 application augmented with Bagisto packages and custom domain features (Blog, Forum, Banner, Job API) running under Docker.

### Core layers

- HTTP
  - routes/web.php, routes/api.php define web and API entry points
  - API rate limiting is applied to specific route groups (e.g., throttles 60/min)
- Application
  - app/Http/* controllers and middleware
  - app/Services/ (e.g., JobService) encapsulates business logic
  - app/Repositories/ (e.g., BlogRepository) implements repository pattern
- Domain models
  - app/Models/* holds custom entities (Blog, Forum*, User)
- Packages (Bagisto)
  - packages/Webkul/* are autoloaded PSR-4 modules (Admin, Core, DataGrid, Shop, etc.) providing EAV, catalog, sales, checkout, etc.

### Packages and EAV

- composer.json maps Webkul namespaces to packages/Webkul/*
- Bagisto’s EAV model underpins catalog-like entities. In this project, Job postings are modeled leveraging EAV-like attributes and relationships, aligning with Bagisto’s product/attribute design.

### Key subsystems

- Blog
  - Custom models and a repository
  - Analysis docs outline current schema and optimization areas
- Forum
  - Proper pivot for tags, nested comments, voting/reporting models
  - Analysis doc details counters, status, and moderation flow
- Dynamic Banner API
  - routes/api.php group at /api/banner serving jobs/topics/blogs/sources/all with throttle
- AI Thumbnail API
  - routes/api.php group at /api/ai/thumbnails for blog/product thumbnails + stats
- Job Posting API
  - routes/api.php group at /api/jobs
  - Public endpoints: list, show, categories, attributes
  - Create/update/delete/bulk/publish/unpublish are currently open (auth commented; ready for Sanctum)

### Frontend assets

- Vite (vite.config.js) builds assets
- NPM scripts: dev, build, prod
- Alpine.js/Bootstrap used client-side

### Infrastructure (Docker Compose)

- Services: php (FPM), nginx, mysql, redis, mailpit, vite
- Traefik integration via labels on nginx and external network `traefik-public`
- PHP container mounts the project and a custom php.ini; OPCache timestamps validation enabled for dev

## Testing topology

- phpunit.xml defines suites for Admin Feature, Core Unit, DataGrid Unit, Shop Feature targeting packages/Webkul/* test paths
- Default testing env is configured via <php> env variables in phpunit.xml

Run a single test quickly

```bash path=null start=null
# Filter by class or method name
./vendor/bin/phpunit --filter JobControllerTest::test_can_list_jobs

# Or via Laravel test runner
php artisan test --filter=JobControllerTest
```

## Notable mismatches and gotchas

- Helper scripts vs compose services
  - scripts/dev.sh and Makefile targets assume a service named `app` for exec/logs; docker-compose.yml defines `php`. Prefer `docker-compose exec php ...` if helper targets fail.
- External dependencies
  - docker-compose.yml references `../emsaigon/docker/php` for build and an external network `traefik-public`. Ensure both are available locally or adapt the compose file.
- Auth in Job API
  - The protected routes are currently uncommented for local testing; enable Sanctum and re-scope middleware before production.

## Mobile-first note

When adjusting templates or adding UI, favor mobile-first behavior. Existing docs and features target Vietnamese content and should render cleanly on small screens; validate responsive behavior for any new UI you introduce.
