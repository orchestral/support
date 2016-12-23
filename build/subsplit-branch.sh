#!/bin/sh

if [ -z "$1" ]; then
    echo "No argument supplied";
    exit 1;
fi

if [ -d .subsplit ]; then
    git subsplit update
else
    git subsplit init git@github.com:orchestral/support.git
fi

git subsplit publish --heads=$1 --no-tags src/Facades:git@github.com:orchestral/support-facades.git
git subsplit publish --heads=$1 --no-tags src/Providers:git@github.com:orchestral/support-providers.git
git subsplit publish --heads=$1 --no-tags src/Support:git@github.com:orchestral/support-core.git
