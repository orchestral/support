#!/usr/bin/env bash

set -e

CURRENT_BRANCH="master"
COMPONENTS=("facades" "providers" "core")

if (( "$#" != 1 ))
then
    echo "Tag has to be provided"

    exit 1
fi
VERSION=$1

# Always prepend with "v"
if [[ $VERSION != v*  ]]
then
    VERSION="v$VERSION"
fi

# Tag Component
git tag $VERSION
git push origin --tags

# Tag Components
for REMOTE in "${COMPONENTS[@]}"
do
    echo ""
    echo ""
    echo "Releasing $REMOTE";

    TMP_DIR="/tmp/orchestra-support-split"
    REMOTE_URL="git@github.com:orchestral/support-$REMOTE.git"

    rm -rf $TMP_DIR;
    mkdir $TMP_DIR;

    (
        cd $TMP_DIR;

        git clone $REMOTE_URL .
        git checkout "$CURRENT_BRANCH";

        git tag $VERSION
        git push origin --tags
    )
done
