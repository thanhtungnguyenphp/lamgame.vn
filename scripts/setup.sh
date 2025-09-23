#!/bin/bash

# Bagisto Docker Development Setup Script
# This script sets up the development environment for Bagisto

set -e

echo "🚀 Setting up Bagisto Docker Development Environment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

print_status "Docker and Docker Compose are installed."

# Check if .env file exists, if not copy from .env.docker
if [ ! -f .env ]; then
    print_step "Creating .env file from .env.docker..."
    cp .env.docker .env
    print_status ".env file created."
else
    print_warning ".env file already exists. Skipping creation."
fi

# Generate APP_KEY if not exists
if ! grep -q "APP_KEY=base64:" .env; then
    print_step "Generating application key..."
    # We'll generate the key after containers are up
    print_status "Application key will be generated after containers start."
fi

# Stop any running containers
print_step "Stopping any running containers..."
docker-compose down 2>/dev/null || true

# Build the application image first
print_step "Building application Docker image..."
docker-compose build app

# Pull other images
print_step "Pulling other Docker images..."
docker-compose pull mysql redis elasticsearch mailpit 2>/dev/null || true

# Start the services
print_step "Starting Docker services..."
docker-compose up -d mysql redis elasticsearch

# Wait for database to be ready
print_step "Waiting for database to be ready..."
sleep 30

# Start the main application
print_step "Starting main application..."
docker-compose up -d app

# Wait for application to be ready
print_step "Waiting for application to be ready..."
sleep 10

# Generate APP_KEY if needed
print_step "Generating application key..."
docker-compose exec -T app php artisan key:generate --force

# Run database migrations
print_step "Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Seed the database (optional)
read -p "Do you want to seed the database with sample data? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_step "Seeding database..."
    docker-compose exec -T app php artisan db:seed --force
fi

# Create storage symlink
print_step "Creating storage symlink..."
docker-compose exec -T app php artisan storage:link

# Clear and optimize caches
print_step "Optimizing application..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

# Show service URLs
print_status "✅ Setup completed successfully!"
echo
echo "🌐 Application URLs:"
echo "   - Main Application: http://localhost:8000"
echo "   - Admin Panel: http://localhost:8000/admin"
echo "   - PhpMyAdmin: http://localhost:8080 (run with --profile dev)"
echo "   - Mailpit (Email): http://localhost:8025"
echo "   - Elasticsearch: http://localhost:9200"
echo "   - Kibana: http://localhost:5601 (run with --profile dev)"
echo
echo "📋 Useful commands:"
echo "   - Start services: docker-compose up -d"
echo "   - Stop services: docker-compose down"
echo "   - View logs: docker-compose logs -f app"
echo "   - Access app container: docker-compose exec app bash"
echo "   - Start with dev tools: docker-compose --profile dev up -d"
echo
echo "🎉 Happy coding!"
