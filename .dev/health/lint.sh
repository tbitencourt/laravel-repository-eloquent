#!/usr/bin/env bash

#set -x

FILES=""

for file in $(git diff --name-only HEAD $(git rev-parse --verify origin/dev) -- app/ database/)
do
    if [[  -f "$file" && ${file: -4} == ".php" ]]; then
        FILES+="$file "
    fi
done

if [ ! -z "$FILES" ]; then
    find -L $FILES -name '*.php' -print0 | xargs -0 -n 1 -P 4 php -l
fi
