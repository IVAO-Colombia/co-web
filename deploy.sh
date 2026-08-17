#!/usr/bin/env bash
#
# Deployment script for IVAO CO Web.
#
# Run this on the server, inside the project root, to deploy the code
# already on disk: installs dependencies, builds frontend assets, runs
# migrations, refreshes caches and restarts queue workers - putting the
# app into maintenance mode for the shortest window possible and always
# bringing it back up, even on failure.
#
# Usage:
#   ./deploy.sh

set -euo pipefail

# --- Helpers --------------------------------------------------------------

BLUE='\033[0;34m'
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

step() { echo -e "${BLUE}==>${NC} $1"; }
success() { echo -e "${GREEN}✔${NC} $1"; }
fail() { echo -e "${RED}✘ $1${NC}" >&2; }

MAINTENANCE_MODE_ON=0

bring_site_back_up() {
    if [ "$MAINTENANCE_MODE_ON" -eq 1 ]; then
        php artisan up
        MAINTENANCE_MODE_ON=0
    fi
}

on_error() {
    fail "Deployment failed. Bringing the site back up."
    bring_site_back_up
    exit 1
}

trap on_error ERR

# --- Preflight checks -------------------------------------------------

if [ ! -f "artisan" ]; then
    fail "artisan not found. Run this script from the project root."
    exit 1
fi

for cmd in composer npm php; do
    if ! command -v "$cmd" >/dev/null 2>&1; then
        fail "Required command '$cmd' not found in PATH."
        exit 1
    fi
done

# --- Maintenance mode ---------------------------------------------------

step "Enabling maintenance mode"
php artisan down --retry=60
MAINTENANCE_MODE_ON=1

# --- PHP dependencies ---------------------------------------------------

step "Installing composer dependencies"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# --- Frontend dependencies & build --------------------------------------

step "Installing npm dependencies"
npm ci

step "Building frontend assets"
npm run build

# --- Database migrations -------------------------------------------------

step "Running database migrations"
php artisan migrate --force

step "Seeding roles and permissions"
php artisan db:seed --class=SpatieRolesAndPermissionsSeeder --force

# --- Storage symlink -------------------------------------------------------

if [ ! -L "public/storage" ]; then
    step "Creating storage symlink"
    php artisan storage:link
fi

# --- Caches ---------------------------------------------------------------

step "Refreshing application caches"
php artisan optimize:clear
php artisan optimize

# --- Queue workers ----------------------------------------------------

step "Restarting queue workers"
php artisan queue:restart

# --- Disable maintenance mode --------------------------------------------

step "Disabling maintenance mode"
bring_site_back_up

success "Deployment complete."
