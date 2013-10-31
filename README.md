# sg

Static site generation template for the [Stories section](http://simongriffee.com/stories/) of SimonGriffee.com

Uses [Jekyll](http://jekyllrb.com/).

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

1. Eventually [transfer all content to Jekyll](http://jekyllrb.com/docs/migrations/)?
2. Set up [static file search via JavaScript](http://developmentseed.org/blog/2011/09/09/jekyll-github-pages/)?

