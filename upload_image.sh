#!/bin/bash

# Stop execution if a step fails
set -e

# Replace with your group's image name
IMAGE_NAME=gitlab.up.pt:5050/lbaw/lbaw2425/lbaw24074

# Ensure that dependencies are available
composer install
php artisan config:clear
php artisan clear-compiled
php artisan optimize

# Build based on architecture
ARCH=$(uname -m)

if [[ "$ARCH" == "arm64" || "$ARCH" == "aarch64" ]]; then
  docker buildx build --push --platform linux/amd64 -t $IMAGE_NAME .
else
  docker build -t $IMAGE_NAME .
  docker push $IMAGE_NAME
fi
