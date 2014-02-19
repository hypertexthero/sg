#!/usr/bin/env ruby
require 'nokogiri'
require 'sequel'

sqlite_db = '_site/search/search.db'
File.delete( sqlite_db ) if File.exists?( sqlite_db )

DB = Sequel.sqlite(sqlite_db)         
DB.run('CREATE TABLE pages(  title text, permalink text, meta_keywords text, meta_description  text, text_content text, search_excerpt text) ;')
puts "Recreated sqlite DB"

search_list = Dir.glob('_site/**/index.html')
# ignore category, tag, feed pages and general pagination
search_list = search_list.reject {|item| item =~ /\/category\/|\/tag\/|\/page\d+\/|\/feed\// }

search_list.each do |page|
  permalink = page.gsub(/^_site/,'').gsub(/index\.html$/,'')
  next if permalink == '/'
  f = File.new(page).read
  doc =  Nokogiri::HTML(f)
  text  = doc.css('.entry').inner_text
  title = doc.css('.title').inner_text
  next if title == ''  # deals with alias generator which creates pages with no content
  
  meta_keywords = doc.xpath("//meta[@name='keywords']/@content").to_s
  meta_description = doc.xpath("//meta[@name='description']/@content").to_s
  search_excerpt = meta_description
   
  insert_pages = DB["INSERT INTO pages (title, permalink, meta_keywords, meta_description, text_content, search_excerpt) VALUES (? , ? , ?, ?, ? , ?)", title, permalink, meta_keywords, meta_description, text, search_excerpt]
  insert_pages.insert
  puts "DB Record created for : #{title} . Permalink : #{permalink} ."
end