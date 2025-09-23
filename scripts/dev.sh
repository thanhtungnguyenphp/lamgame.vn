#!/bin/bash

# Bagisto Development Utilities Script
# Provides common development tasks

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_usage() {
    echo "Bagisto Development Utilities"
    echo ""
    echo "Usage: $0 [COMMAND]"
    echo ""
    echo "Commands:"
    echo "  start           Start all services"
    echo "  start-dev       Start all services with development tools"
    echo "  stop            Stop all services"
    echo "  restart         Restart all services"
    echo "  logs            View application logs"
    echo "  shell           Access application container shell"
    echo "  artisan [cmd]   Run artisan command"
    echo "  composer [cmd]  Run composer command"
    echo "  npm [cmd]       Run npm command"
    echo "  test            Run tests"
    echo "  migrate         Run database migrations"
    echo "  seed            Seed the database"
    echo "  fresh           Fresh migrate and seed"
    echo "  cache-clear     Clear all caches"
    echo "  optimize        Optimize application (cache configs, routes, views)"
    echo "  reset           Reset application (clear caches, fresh migrate)"
    echo "  backup-db       Backup database"
    echo "  restore-db      Restore database from backup"
    echo "  status          Show services status"
    echo ""
}

print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# Check if docker-compose is available
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed."
    exit 1
fi

case "$1" in
    "start")
        print_step "Starting all services..."
        docker-compose up -d
        print_status "Services started successfully!"
        ;;
    
    "start-dev")
        print_step "Starting all services with development tools..."
        docker-compose --profile dev up -d
        print_status "Services with dev tools started successfully!"
        ;;
    
    "stop")
        print_step "Stopping all services..."
        docker-compose down
        print_status "Services stopped successfully!"
        ;;
    
    "restart")
        print_step "Restarting all services..."
        docker-compose restart
        print_status "Services restarted successfully!"
        ;;
    
    "logs")
        print_step "Viewing application logs..."
        docker-compose logs -f app
        ;;
    
    "shell")
        print_step "Accessing application container shell..."
        docker-compose exec app bash
        ;;
    
    "artisan")
        shift
        print_step "Running artisan command: $@"
        docker-compose exec app php artisan "$@"
        ;;
    
    "composer")
        shift
        print_step "Running composer command: $@"
        docker-compose exec app composer "$@"
        ;;
    
    "npm")
        shift
        print_step "Running npm command: $@"
        docker-compose exec app npm "$@"
        ;;
    
    "test")
        print_step "Running tests..."
        docker-compose exec app php artisan test
        ;;
    
    "migrate")
        print_step "Running database migrations..."
        docker-compose exec app php artisan migrate
        print_status "Migrations completed!"
        ;;
    
    "seed")
        print_step "Seeding database..."
        docker-compose exec app php artisan db:seed
        print_status "Database seeded!"
        ;;
    
    "fresh")
        print_step "Fresh migrating and seeding database..."
        docker-compose exec app php artisan migrate:fresh --seed
        print_status "Fresh migration and seed completed!"
        ;;
    
    "cache-clear")
        print_step "Clearing all caches..."
        docker-compose exec app php artisan config:clear
        docker-compose exec app php artisan cache:clear
        docker-compose exec app php artisan route:clear
        docker-compose exec app php artisan view:clear
        print_status "All caches cleared!"
        ;;
    
    "optimize")
        print_step "Optimizing application..."
        docker-compose exec app php artisan config:cache
        docker-compose exec app php artisan route:cache
        docker-compose exec app php artisan view:cache
        print_status "Application optimized!"
        ;;
    
    "reset")
        print_step "Resetting application..."
        docker-compose exec app php artisan config:clear
        docker-compose exec app php artisan cache:clear
        docker-compose exec app php artisan route:clear
        docker-compose exec app php artisan view:clear
        docker-compose exec app php artisan migrate:fresh --seed
        print_status "Application reset completed!"
        ;;
    
    "backup-db")
        print_step "Backing up database..."
        BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
        docker-compose exec mysql mysqldump -u bagisto -psecret bagisto > "./backups/$BACKUP_FILE"
        print_status "Database backed up to ./backups/$BACKUP_FILE"
        ;;
    
    "restore-db")
        if [ -z "$2" ]; then
            print_error "Please specify backup file: $0 restore-db backup_file.sql"
            exit 1
        fi
        print_step "Restoring database from $2..."
        docker-compose exec -T mysql mysql -u bagisto -psecret bagisto < "./backups/$2"
        print_status "Database restored from $2"
        ;;
    
    "status")
        print_step "Services status:"
        docker-compose ps
        ;;
    
    *)
        print_usage
        exit 1
        ;;
esac
