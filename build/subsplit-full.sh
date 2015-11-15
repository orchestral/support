#!/bin/sh

if [ -d .subsplit ]; then
    git subsplit update
else
    git subsplit init git@github.com:orchestral/support.git
fi

git subsplit publish --heads="master 3.0 3.1 3.2" src/Facades:git@github.com:orchestral/support-facades.git
git subsplit publish --heads="master 3.0 3.1 3.2" src/Providers:git@github.com:orchestral/support-providers.git
git subsplit publish --heads="master 3.0 3.1 3.2" src/Support:git@github.com:orchestral/support-core.git
