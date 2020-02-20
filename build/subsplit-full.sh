#!/bin/sh

if [ -d .subsplit ]; then
    git subsplit update
else
    git subsplit init git@github.com:orchestral/support.git
fi

git subsplit publish --heads="5.x" src/Facades:git@github.com:orchestral/support-facades.git
git subsplit publish --heads="5.x" src/Providers:git@github.com:orchestral/support-providers.git
git subsplit publish --heads="5.x" src/Support:git@github.com:orchestral/support-core.git
