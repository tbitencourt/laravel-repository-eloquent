#!/usr/bin/env bash

#set -x

FILES=""

for file in $(git diff --name-only HEAD $(git rev-parse --verify origin/dev) -- app/ database/)
do
    if [[  -f "$file" && ${file: -4} == ".php" ]]; then
        #php vendor/bin/phpmd ${file} text ./phpmd-ruleset.xml --suffixes=php,phtml
        php vendor/bin/phpmd ${file} text cleancode, codesize, controversial, design, naming, unusedcode
    fi
done
