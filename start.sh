#!/bin/bash

# Set default port if not provided
PORT=${PORT:-8000}

# Cast PORT to an integer to avoid type issues in Laravel's ServeCommand
PORT_INT=$(($PORT + 0)) # This forces arithmetic evaluation, converting to int

# Wait for a moment to ensure everything is ready
sleep 2

# Clear any cached config
php artisan config:clear

# Start Laravel server
echo "Starting Laravel on port $PORT_INT..."
php artisan serve --host=0.0.0.0 --port=$PORT_INT 