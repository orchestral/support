#!/bin/sh
git subsplit init git@github.com:orchestral/support.git
git subsplit publish --heads="master" src/Facades:git@github.com:orchestral/support-facades.git
git subsplit publish --heads="master" src/Providers:git@github.com:orchestral/support-providers.git
git subsplit publish --heads="master 2.1 2.2" src/Support:git@github.com:orchestral/support-core.git
rm -rf .subsplit/
