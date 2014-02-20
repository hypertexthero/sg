# sg

Static site generation template for the [Stories section](http://simongriffee.com/stories/) of SimonGriffee.com

Uses [Jekyll](http://jekyllrb.com/).

## Preview site locally

    jekyll serve -w

## Automatic deployment with Git

A. Make sure [Jekyll and RubyGems are installed](http://jekyllrb.com/docs/installation/) on both local and server machines.

B. On local:

`~/projects/sg/.git/config`:

    [core]
        repositoryformatversion = 0
        filemode = true
        bare = false
        logallrefupdates = true
        ignorecase = true
    [remote "origin"]
        url = git@github.com:hypertexthero/sg.git
        fetch = +refs/heads/*:refs/remotes/origin/*
    [branch "master"]
        remote = origin
        merge = refs/heads/master
    [remote "deploy"]
        url = hth@hth.webfactional.com:~/repos/sg.git
        fetch = +refs/heads/*:refs/remotes/deploy/*
    [remote "all"]
        url = hth@hth.webfactional.com:~/repos/sg.git
        url = git@github.com:hypertexthero/sg.git

C. On server:

Make sure these folders exist:

`~/webapps/simongriffee/stories/`  
`~/repos/sg/`  
`~/repos/sg.it/`

Put the following in `~/repos/sg.it/hooks/post-receive`:

    #!/bin/sh

    GIT_REPO=$HOME/repos/sg.git
    TMP_GIT_CLONE=/home/hth/tmp/sg
    PUBLIC_WWW=/home/hth/webapps/simongriffee/stories

    git clone $GIT_REPO $TMP_GIT_CLONE
    jekyll build -s $TMP_GIT_CLONE -d $PUBLIC_WWW
    rm -Rf $TMP_GIT_CLONE
    exit

Run this command to make it executable:

    chmod +x post-receive

D. To deploy:

    git add .
    git commit -m 'short description of change'
    git push all

## Things to do

0. Homepage design: stories' titles and main images slideshow with pause/play links. Links to other sections.
1. [Search via JavaScript](http://developmentseed.org/blog/2011/09/09/jekyll-github-pages/) or DuckDuckGo.
2. Refactor stories CSS into main stylesheet
3. Setup [redirects to .html](http://stackoverflow.com/a/16773980) and [HTTPS](https://docs.webfaction.com/software/static.html#static-redirecting-from-http-to-https)
4. Add existing folders to .gitignore and test files don't get overwritten on push
5. [HTTPS](https://docs.webfaction.com/user-guide/websites.html#secure-sites-https)