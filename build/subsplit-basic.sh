git subsplit init git@github.com:orchestral/support.git
git subsplit publish --heads="master" --no-tags src/Facades:git@github.com:orchestral/support-facades.git
git subsplit publish --heads="master" --no-tags src/Providers:git@github.com:orchestral/support-providers.git
git subsplit publish --heads="master 2.1 2.2" --no-tags src/Support:git@github.com:orchestral/support-core.git
rm -rf .subsplit/
