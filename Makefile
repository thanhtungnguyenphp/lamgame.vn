.PHONY: help setup start start-dev stop restart logs shell artisan composer npm test migrate seed fresh cache-clear optimize reset backup restore status

# Default target
help: ## Show this help message
	@echo 'Bagisto Docker Development Environment'
	@echo ''
	@echo 'Usage:'
	@echo '  make [target]'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

setup: ## Initial setup of the development environment
	@./scripts/setup.sh

start: ## Start all services
	@./scripts/dev.sh start

start-dev: ## Start all services with development tools
	@./scripts/dev.sh start-dev

stop: ## Stop all services
	@./scripts/dev.sh stop

restart: ## Restart all services
	@./scripts/dev.sh restart

logs: ## View application logs
	@./scripts/dev.sh logs

shell: ## Access application container shell
	@./scripts/dev.sh shell

artisan: ## Run artisan command (usage: make artisan cmd="migrate")
	@./scripts/dev.sh artisan $(cmd)

composer: ## Run composer command (usage: make composer cmd="install")
	@./scripts/dev.sh composer $(cmd)

npm: ## Run npm command (usage: make npm cmd="install")
	@./scripts/dev.sh npm $(cmd)

test: ## Run tests
	@./scripts/dev.sh test

migrate: ## Run database migrations
	@./scripts/dev.sh migrate

seed: ## Seed the database
	@./scripts/dev.sh seed

fresh: ## Fresh migrate and seed database
	@./scripts/dev.sh fresh

cache-clear: ## Clear all caches
	@./scripts/dev.sh cache-clear

optimize: ## Optimize application
	@./scripts/dev.sh optimize

reset: ## Reset application (clear caches, fresh migrate)
	@./scripts/dev.sh reset

backup: ## Backup database
	@./scripts/dev.sh backup-db

restore: ## Restore database from backup (usage: make restore file="backup.sql")
	@./scripts/dev.sh restore-db $(file)

status: ## Show services status
	@./scripts/dev.sh status

# Quick commands
up: start ## Alias for start
down: stop ## Alias for stop
build: ## Build Docker images
	@docker-compose build

rebuild: ## Rebuild Docker images without cache
	@docker-compose build --no-cache

clean: ## Clean up Docker resources
	@docker-compose down -v --remove-orphans
	@docker system prune -f
