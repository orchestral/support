#!/bin/sh

if [ -d .subsplit ]; then
    git subsplit update
else
    git subsplit init git@github.com:orchestral/support.git
fi

git subsplit publish --heads="master 3.5 3.4 3.3 3.1" src/Facades:git@github.com:orchestral/support-facades.git
git subsplit publish --heads="master 3.5 3.4 3.3 3.1" src/Providers:git@github.com:orchestral/support-providers.git
git subsplit publish --heads="master 3.5 3.4 3.3 3.1" src/Support:git@github.com:orchestral/support-core.git
