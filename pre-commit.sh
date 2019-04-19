#!/bin/bash

EXIT_STATUS=0
REGEX=".*php$"

#
# Coding style check per PHP file
#
for file in $(git diff --cached --name-only --diff-filter=ACM); do
    if [[ $file =~ $REGEX ]]; then
        composer cs-check $file

        EXIT_STATUS=$?

        if [ $EXIT_STATUS -ne 0 ]; then
            echo "Issues detected! To fix, execute: composer cs-fix $file"

            exit $EXIT_STATUS
        fi
    fi
done

if [ $EXIT_STATUS -eq 0 ]; then
    echo "All good! No coding style issues found :)"
fi

exit $EXIT_STATUS