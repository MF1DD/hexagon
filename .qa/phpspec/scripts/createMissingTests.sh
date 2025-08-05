#!/usr/bin/env bash

source="/app/src"
GREEN="\033[0;32m"
YELLOW="\033[1;33m"
CYAN="\033[0;36m"
RESET="\033[0m"

find "$source" -name "*.php" | while read file; do
    namespace=$(grep -E "^namespace " "$file" | sed 's/^namespace \(.*\);$/\1/' | tr -d '\n')
    classname=$(basename "$file" .php)
    fullclass="${namespace}\\${classname}"

    echo -e "${CYAN}🎯 Describe:${RESET} $fullclass"
    composer test:phpspec-init -- "$fullclass" -n
done
