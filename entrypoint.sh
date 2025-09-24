#!/bin/bash

# Check if the application code exists in the volume
if [ ! -f /var/www/.initialized ]; then
    echo "Initializing application code in the volume..."
    cp -r /var/www/* /var/www-volume/
    touch /var/www-volume/.initialized
fi

# Start the main process
exec "$@"