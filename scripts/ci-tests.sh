#!/bin/sh

# Run unit tests inside of docker
cd /code/vendor/erdiko/session/tests/
phpunit AllTests
