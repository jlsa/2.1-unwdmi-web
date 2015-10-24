# Git(hub) Workflow

This document is both a quick introduction to some command-line Git & a
description of how we'll use Git and Github for this project specifically.

It's quite strict, and in reality we'll be a bit more loose. This just describes
a "best-case" scenario.

## Making Changes

Before you make _any_ change, branch off `master`. Try _not_ to branch off other
branches, because they might get rebased or rewritten, and if that happens your
branch will get super difficult to merge.

To branch off master:

```bash
git checkout master
git checkout -b my-epic-feature # "-b" creates a new branch
```

Then commit your changes on the `my-epic-feature-branch`. Try to keep commits
_small_ and _UNIXy_. Basically, don't commit a bunch of stuff that does a lot of
different things, but make multiple small commits that do simple, small things.

```bash
# "-p" allows you to review the changes while adding them, and it also allows
# you to commit only some parts of a file.
git add -p changed/file.php
# "-m" allows you to set a commit message quickly.
git commit -m "Cache Rainfall queries for 2 hours"
# If you need more than say, 70 or 80 characters to explain your commit, leave
# off the "-m", and Git will open an editor for your commit message.
# Describe your commit briefly on the first line, in 70-80 characters max, and
# add a more elaborate explanation after two newlines.
git commit
# Example message:
# > Speed up Rainfall query
# >
# > Introduce a RainfallCache to cache the full JSON response data for a while
# > so that subsequent calls to the /data/rainfall.json endpoint take only a few
# > ms.
```

Before committing, it's a good idea to have PHPCS or ESlint check your code
style:

```bash
# if you worked on the PHP side of things:
php artisan cs:php
# if you worked on the JS side of things:
php artisan cs:js
```

When you're mostly done with your branch, push it to Github using:

```bash
git push origin my-epic-feature # of course, insert your own branch name
```

Then, open your branch in Github and press the green Pull Request button. This
will allow other project members to review your code. They'll merge it when
they're done.

## Rebasing

If other branches have been merged to master while you were working on your
branch, you might need to rebase your branch.

```bash
git rebase master my-epic-feature # insert your own branch name
```

You might run into merge conflicts here. If you're not sure what to do at that
point, poke [@IcyPalm](https://github.com/IcyPalm) or [@goto-bus-stop](https://github.com/goto-bus-stop),
because resolving conflicts is much easier to explain IRL. Or you can ask
[DuckDuckGo](https://duckduckgo.com/?q=resolving+merge+conflicts) if you like.

After a rebase, your commit history will be rewritten. That means that you can't
push your branch to Github as you're used to. Instead, you have to force-push
it, _overwriting_ the commit history on Github.

**ONLY EVER DO THIS FOR YOUR OWN BRANCHES.** Every time someone force-pushes
to master, a kitten dies. Don't be mean.

```bash
git push origin -f my-epic-feature
```
