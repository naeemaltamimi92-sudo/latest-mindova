#!/bin/bash

# Mindova Deployment Script
# Usage: ./deploy.sh [--skip-build] [--skip-migrations]

set -e

# Configuration
SERVER_IP="116.203.245.50"
SERVER_USER="root"
REMOTE_PATH="/var/www/mindova"
LOCAL_PATH="/Users/awni/Desktop/naeem/latest-mindova"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Parse arguments
SKIP_BUILD=false
SKIP_MIGRATIONS=false

for arg in "$@"; do
    case $arg in
        --skip-build)
            SKIP_BUILD=true
            shift
            ;;
        --skip-migrations)
            SKIP_MIGRATIONS=true
            shift
            ;;
        --help)
            echo "Usage: ./deploy.sh [OPTIONS]"
            echo ""
            echo "Options:"
            echo "  --skip-build       Skip npm install and build"
            echo "  --skip-migrations  Skip database migrations"
            echo "  --help             Show this help message"
            exit 0
            ;;
    esac
done

# Functions
print_step() {
    echo -e "\n${BLUE}==>${NC} ${GREEN}$1${NC}"
}

print_warning() {
    echo -e "${YELLOW}Warning:${NC} $1"
}

print_error() {
    echo -e "${RED}Error:${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

# Check SSH connection
check_connection() {
    print_step "Checking server connection..."
    if ssh -o ConnectTimeout=10 -o BatchMode=yes ${SERVER_USER}@${SERVER_IP} "echo 'connected'" &>/dev/null; then
        print_success "Server connection OK"
    else
        print_error "Cannot connect to server. Check SSH keys and server status."
        exit 1
    fi
}

# Sync files to server
sync_files() {
    print_step "Syncing files to server..."

    rsync -avz --delete \
        --exclude 'node_modules' \
        --exclude 'vendor' \
        --exclude '.git' \
        --exclude '.env' \
        --exclude 'storage/logs/*.log' \
        --exclude 'storage/framework/cache/data/*' \
        --exclude 'storage/framework/sessions/*' \
        --exclude 'storage/framework/views/*' \
        --exclude 'storage/app/public/*' \
        --exclude '.DS_Store' \
        --exclude 'deploy.sh' \
        ${LOCAL_PATH}/ ${SERVER_USER}@${SERVER_IP}:${REMOTE_PATH}/

    print_success "Files synced successfully"
}

# Install PHP dependencies
install_composer() {
    print_step "Installing PHP dependencies..."

    ssh ${SERVER_USER}@${SERVER_IP} "cd ${REMOTE_PATH} && \
        export COMPOSER_ALLOW_SUPERUSER=1 && \
        composer install --no-dev --optimize-autoloader --no-interaction"

    print_success "PHP dependencies installed"
}

# Install Node dependencies and build
build_assets() {
    if [ "$SKIP_BUILD" = true ]; then
        print_warning "Skipping npm build (--skip-build flag)"
        return
    fi

    print_step "Installing Node dependencies..."
    ssh ${SERVER_USER}@${SERVER_IP} "cd ${REMOTE_PATH} && npm install"

    print_step "Building assets..."
    ssh ${SERVER_USER}@${SERVER_IP} "cd ${REMOTE_PATH} && npm run build"

    print_success "Assets built successfully"
}

# Run migrations
run_migrations() {
    if [ "$SKIP_MIGRATIONS" = true ]; then
        print_warning "Skipping migrations (--skip-migrations flag)"
        return
    fi

    print_step "Running database migrations..."
    ssh ${SERVER_USER}@${SERVER_IP} "cd ${REMOTE_PATH} && php artisan migrate --force"

    print_success "Migrations completed"
}

# Clear and rebuild caches
optimize_laravel() {
    print_step "Optimizing Laravel..."

    ssh ${SERVER_USER}@${SERVER_IP} "cd ${REMOTE_PATH} && \
        php artisan config:cache && \
        php artisan route:cache && \
        php artisan view:cache && \
        php artisan event:cache"

    print_success "Laravel optimized"
}

# Set permissions
set_permissions() {
    print_step "Setting file permissions..."

    ssh ${SERVER_USER}@${SERVER_IP} "chown -R www-data:www-data ${REMOTE_PATH} && \
        chmod -R 755 ${REMOTE_PATH} && \
        chmod -R 775 ${REMOTE_PATH}/storage && \
        chmod -R 775 ${REMOTE_PATH}/bootstrap/cache"

    print_success "Permissions set"
}

# Restart queue workers
restart_queue() {
    print_step "Restarting queue workers..."

    ssh ${SERVER_USER}@${SERVER_IP} "cd ${REMOTE_PATH} && \
        php artisan queue:restart && \
        supervisorctl restart mindova-worker:*"

    print_success "Queue workers restarted"
}

# Reload Nginx
reload_nginx() {
    print_step "Reloading Nginx..."

    ssh ${SERVER_USER}@${SERVER_IP} "nginx -t && systemctl reload nginx"

    print_success "Nginx reloaded"
}

# Verify deployment
verify_deployment() {
    print_step "Verifying deployment..."

    HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://mindova.net)

    if [ "$HTTP_STATUS" = "200" ]; then
        print_success "Site is live (HTTP $HTTP_STATUS)"
    else
        print_warning "Site returned HTTP $HTTP_STATUS"
    fi

    # Check queue workers
    WORKER_STATUS=$(ssh ${SERVER_USER}@${SERVER_IP} "supervisorctl status mindova-worker:* | grep RUNNING | wc -l")
    print_success "Queue workers running: $WORKER_STATUS"
}

# Main deployment flow
main() {
    echo -e "${GREEN}"
    echo "╔══════════════════════════════════════════╗"
    echo "║       Mindova Deployment Script          ║"
    echo "║       Target: mindova.net                ║"
    echo "╚══════════════════════════════════════════╝"
    echo -e "${NC}"

    START_TIME=$(date +%s)

    check_connection
    sync_files
    install_composer
    build_assets
    run_migrations
    optimize_laravel
    set_permissions
    restart_queue
    reload_nginx
    verify_deployment

    END_TIME=$(date +%s)
    DURATION=$((END_TIME - START_TIME))

    echo ""
    echo -e "${GREEN}╔══════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║       Deployment Complete!               ║${NC}"
    echo -e "${GREEN}║       Duration: ${DURATION}s                        ║${NC}"
    echo -e "${GREEN}║       URL: https://mindova.net           ║${NC}"
    echo -e "${GREEN}╚══════════════════════════════════════════╝${NC}"
}

# Run main function
main
