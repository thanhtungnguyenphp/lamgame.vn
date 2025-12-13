# LamGame.vn Project Makefile
.PHONY: help up down restart logs shell bash artisan composer npm migrate fresh seed cache-clear build rebuild

SHELL := /bin/bash

help: ## Show this help message
	@echo 'LamGame.vn Development Commands'
	@echo '================================'
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

# ==============================================================================
# Docker Commands
# ==============================================================================

up: ## Start project services (PHP + Nginx)
	@echo "🚀 Starting lamgame.vn..."
	docker compose up -d
	@echo "✅ LamGame started!"
	@echo "📱 HTTP:  http://lamgame.localhost"
	@echo "🔒 HTTPS: https://lamgame.localhost"

down: ## Stop project services
	@echo "🔴 Stopping lamgame.vn..."
	docker compose down

restart: down up ## Restart project services

logs: ## Show logs
	docker compose logs -f --tail=50

logs-php: ## Show PHP logs only
	docker compose logs -f --tail=50 php

logs-nginx: ## Show Nginx logs only
	docker compose logs -f --tail=50 nginx

status: ## Show container status
	@docker ps --filter "name=lamgame-"

# ==============================================================================
# Development Commands
# ==============================================================================

shell: ## Open shell in PHP container
	docker exec -it lamgame-php bash

bash: shell ## Alias for shell

artisan: ## Run artisan command (usage: make artisan CMD="migrate")
	@if [ -z "$(CMD)" ]; then \
		echo "❌ Usage: make artisan CMD=\"command\""; \
		echo "Example: make artisan CMD=\"migrate\""; \
		exit 1; \
	fi
	docker exec lamgame-php php artisan $(CMD)

composer: ## Run composer command (usage: make composer CMD="install")
	@if [ -z "$(CMD)" ]; then \
		echo "❌ Usage: make composer CMD=\"command\""; \
		exit 1; \
	fi
	docker exec lamgame-php composer $(CMD)

npm: ## Run npm command (usage: make npm CMD="install")
	@if [ -z "$(CMD)" ]; then \
		echo "❌ Usage: make npm CMD=\"command\""; \
		exit 1; \
	fi
	docker exec lamgame-php npm $(CMD)

# ==============================================================================
# Laravel Commands
# ==============================================================================

migrate: ## Run database migrations
	docker exec lamgame-php php artisan migrate

migrate-fresh: ## Fresh migrations (⚠️ drops all tables)
	@echo "⚠️  This will drop all tables. Continue? [y/N]" && read ans && [ $${ans:-N} = y ]
	docker exec lamgame-php php artisan migrate:fresh

seed: ## Seed the database
	docker exec lamgame-php php artisan db:seed

fresh: ## Fresh migrate and seed (⚠️ drops all data)
	@echo "⚠️  This will drop all data. Continue? [y/N]" && read ans && [ $${ans:-N} = y ]
	docker exec lamgame-php php artisan migrate:fresh --seed

cache-clear: ## Clear all caches
	docker exec lamgame-php php artisan cache:clear
	docker exec lamgame-php php artisan config:clear
	docker exec lamgame-php php artisan route:clear
	docker exec lamgame-php php artisan view:clear

optimize: ## Optimize application
	docker exec lamgame-php php artisan optimize
	docker exec lamgame-php php artisan config:cache
	docker exec lamgame-php php artisan route:cache
	docker exec lamgame-php php artisan view:cache

key-generate: ## Generate application key
	docker exec lamgame-php php artisan key:generate

storage-link: ## Create storage symlink
	docker exec lamgame-php php artisan storage:link

# ==============================================================================
# Database Commands (uses shared-mysql)
# ==============================================================================

db-backup: ## Backup database
	@mkdir -p backups
	@echo "💾 Backing up lamgame database..."
	@cd ../../ && make backup-db DB=lamgame
	@echo "✅ Backup completed!"

db-restore: ## Restore database (usage: make db-restore FILE=backups/lamgame_20231128.sql)
	@if [ -z "$(FILE)" ]; then \
		echo "❌ Usage: make db-restore FILE=backups/lamgame_20231128.sql"; \
		exit 1; \
	fi
	@cd ../../ && make restore-db DB=lamgame FILE=$(FILE)

db-shell: ## Open MySQL shell for lamgame database
	@cd ../../ && docker exec -it shared-mysql mysql -ulamgame -plamgame lamgame

# ==============================================================================
# Build Commands
# ==============================================================================

build: ## Build Docker images
	docker compose build

rebuild: ## Rebuild Docker images without cache
	docker compose build --no-cache

# ==============================================================================
# Setup Commands
# ==============================================================================

setup: ## Initial project setup
	@echo "🔧 Setting up lamgame.vn..."
	@echo ""
	@echo "📝 Step 1: Create database in shared-mysql"
	@cd ../../ && make db-create DB=lamgame USER=lamgame PASS=lamgame
	@echo ""
	@echo "📝 Step 2: Starting services"
	@$(MAKE) up
	@echo ""
	@echo "📝 Step 3: Installing dependencies"
	docker exec lamgame-php composer install
	@echo ""
	@echo "📝 Step 4: Generate app key"
	docker exec lamgame-php php artisan key:generate
	@echo ""
	@echo "📝 Step 5: Run migrations"
	docker exec lamgame-php php artisan migrate --seed
	@echo ""
	@echo "📝 Step 6: Create storage link"
	docker exec lamgame-php php artisan storage:link
	@echo ""
	@echo "✅ Setup complete!"
	@echo "📱 Access: https://lamgame.localhost"

test: ## Run tests
	docker exec lamgame-php php artisan test

# ==============================================================================
# Quick Shortcuts
# ==============================================================================

ps: status ## Alias for status
