--- 
layout: entry
title: "Site Software Note"
excerpt: Notes on the updated publishing system of this website.
image:
location: 
date_taken: 
camera:
lens:
category: notebook
tags:
---

SimonGriffee.com is now a static site with [custom dynamic bits](http://www.businessguide.co.uk/blog/jekyll-search-ways-to-search-a-static-site/) using the open source [Jekyll](http://jekyllrb.com/ "Known as a static site generator, Jekyll generates the files for the site on my personal computer. I then transfer them to the server.") publishing software. For you, dear visitor, the main gain is that the site is faster. To me, it is more secure, simpler and more lasting (HTML and plain text are the analogue film of the software world).

Here's the modified `textpattern.rb` script I used to pull the content from my previous software (Textpattern, which has served me well for years. Thanks, [Dean](https://twitter.com/textism)). It is [modified version](https://gist.github.com/hypertexthero/9a9d51aeef78742ccfc1) of the one [here](http://gath.co.nz/bhalg/migrating-from-textpattern-to-octopress/). On my computer this was at `/Users/Simon/.rbenv/versions/1.9.2-p290/lib/ruby/gems/1.9.1/gems/jekyll-import-0.1.0/lib/jekyll-import/importers`.

