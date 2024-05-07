#!/bin/bash
if [ "$(docker ps -q -f name=php)" ]; then
    docker exec -it lumina-php-1 php "$@"
else
    echo "Container not running"
fi
