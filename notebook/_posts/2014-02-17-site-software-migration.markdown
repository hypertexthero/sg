--- 
layout: entry
title: "Site Software Note"
excerpt: Site software note mainly for computer geeks.
image:
location: 
date_taken: 
camera:
lens:
category: notebook
tags:
---

SimonGriffee.com is now a static site with [custom dynamic bits](http://www.businessguide.co.uk/blog/jekyll-search-ways-to-search-a-static-site/) using the open source [Jekyll](http://jekyllrb.com/ "Known as a static site generator, Jekyll generates the files for the site on my personal computer. I then transfer them to the server.") publishing software. For you, dear visitor, the main gain is that the site is faster. To me, it is more secure, simpler and more lasting (HTML and plain text are the analogue film of the software world).

Here's the modified `textpattern.rb` script I used to pull the content from my previous software (Textpattern, which has served me well for years. Thanks, [Dean](https://twitter.com/textism)). It is modified version of the one [here](http://gath.co.nz/bhalg/migrating-from-textpattern-to-octopress/). On my computer this was at `/Users/Simon/.rbenv/versions/1.9.2-p290/lib/ruby/gems/1.9.1/gems/jekyll-import-0.1.0/lib/jekyll-import/importers`:

~~~
    # NOTE: This converter requires Sequel and the MySQL gems.
    # The MySQL gem can be difficult to install on OS X. Once you have MySQL
    # installed, running the following commands should work:
    # $ sudo gem install sequel
    # $ sudo gem install mysql -- --with-mysql-config=/usr/local/mysql/bin/mysql_config

    module JekyllImport
      module Importers
        class TextPattern < Importer
          # Reads a MySQL database via Sequel and creates a post file for each post.
          # The only posts selected are those with a status of 4 or 5, which means
          # "live" and "sticky" respectively.
          # Other statuses are 1 => draft, 2 => hidden and 3 => pending.
      
          # http://simongriffee.com/textpattern/index.php?event=prefs&step=advanced_prefs
          # custom_1 - Location
          # custom_2 - Date
          # custom_3 - Camera
          # custom_4 - Lens

          QUERY = "SELECT Title, \
                          url_title, \
                          Image, \
                          custom_1, \ 
                          custom_2, \
                          custom_3, \
                          custom_4, \                    
                          Excerpt, \
                          Section, \
                          Posted, \
                          Body, \
                          Keywords \
                   FROM textpattern \
                   WHERE Status = '4' OR \
                         Status = '5'"

          def self.require_deps
            JekyllImport.require_with_fallback(%w[
              rubygems
              sequel
              fileutils
              safe_yaml
            ])
          end

          def self.specify_options(c)
            c.option 'dbname', '--dbname DB', 'Database name'
            c.option 'user', '--user USER', 'Database user name'
            c.option 'password', '--password PW', "Database user's password"
            c.option 'host', '--host HOST', 'Database host name (default: "localhost")'
          end

          def self.process(options)
            dbname = options.fetch('dbname')
            user   = options.fetch('user')
            pass   = options.fetch('password', "")
            host   = options.fetch('host', "localhost")

            db = Sequel.mysql(dbname, :user => user, :password => pass, :host => host, :encoding => 'utf8')

            FileUtils.mkdir_p "_posts"

            db[QUERY].each do |post|
              # Get required fields and construct Jekyll compatible name.
              title = post[:Title]
              excerpt = post[:Excerpt]
              image = post[:Image]
              location = post[:custom_1]
              date_taken = post[:custom_2]
              camera = post[:custom_3]
              lens = post[:custom_4]
              slug = post[:url_title]
              date = post[:Posted]
              excerpt = post[:Excerpt]
              category = post[:Section]
              content = post[:Body]

              name = [date.strftime("%Y-%m-%d"), slug].join('-') + ".textile"

              # Get the relevant fields as a hash, delete empty fields and convert
              # to YAML for the header.
              data = {
                 'layout' => 'post',
                 'title' => title.to_s,
                 'image' => image.to_s,
                 'location' => location.to_s,
                 'date_taken' => date_taken.to_s,
                 'camera' => camera.to_s,
                 'lens' => lens.to_s,
                 'excerpt' => excerpt.to_s,
                 'category' => category.to_s,
                 'tags' => post[:Keywords].split(',')
               }.delete_if { |k,v| v.nil? || v == ''}.to_yaml

              # Write out the data and content to file.
              File.open("_posts/#{name}", "w") do |f|
                f.puts data
                f.puts "---"
                f.puts content
              end
            end
          end
        end
      end
    end

~~~

