#!/usr/bin/env bash

set -e
set -x

CURRENT_BRANCH="4.x"

function split()
{
    SHA1=`splitsh-lite --prefix=$1`
    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
}

function add_remote()
{
    git remote add $1 $2 || true
}

function remove_remote()
{
    git remote remove $1 || true
}

git pull origin $CURRENT_BRANCH

add_remote core git@github.com:orchestral/support-core.git
add_remote facades git@github.com:orchestral/support-facades.git
add_remote providers git@github.com:orchestral/support-providers.git

split 'src/Support' core
split 'src/Facades' facades
split 'src/Providers' providers

remove_remote core
remove_remote facades
remove_remote providers
