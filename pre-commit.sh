#!/bin/bash

CHANGED=$(git diff --cached --name-only --diff-filter=ACM | grep -E '\.php$' || true)

if [ -z "$CHANGED" ]; then
    exit 0
fi

echo "Running Pint on staged PHP files..."
if ! ./vendor/bin/pint --test $CHANGED; then
    echo
    echo "Pint failures detected. Fix with: ./vendor/bin/pint $CHANGED"
    exit 1
fi

echo "All good."
