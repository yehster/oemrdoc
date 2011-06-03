require 'rubygems'
require 'watir-webdriver'

  browser = Watir::Browser.new :firefox
  browser.goto "http://google.com"
  browser.text_field(:name => 'q').set("ruby watir watir-webdriver")
  browser.button(:name => 'btnG').click
  puts browser.url
  browser.close
