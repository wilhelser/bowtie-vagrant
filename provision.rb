#!/usr/bin/env ruby

class String
def black;          "\033[30m#{self}\033[0m" end
def red;            "\033[31m#{self}\033[0m" end
def green;          "\033[32m#{self}\033[0m" end
def brown;          "\033[33m#{self}\033[0m" end
def blue;           "\033[34m#{self}\033[0m" end
def magenta;        "\033[35m#{self}\033[0m" end
def cyan;           "\033[36m#{self}\033[0m" end
def gray;           "\033[37m#{self}\033[0m" end
end
puts ' '
puts 'WARNING: This will destroy the current WP site in this box if one exists.'.red
puts ' '
puts '  IIIIIIIIIIIIIIIIIIIIIIIIII                        IIIIIIIIIIIIIIIIIIIIIIIIII  '.green
puts 'IIIIIIIIIIIIIIIIIIIIIIIIIIIIII                    IIIIIIIIIIIIIIIIIIIIIIIIIIIIII'.green
puts 'IIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII                IIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII'.green
puts 'IIIIII                  IIIIIIIII            IIIIIIIII                    IIIIII'.green
puts 'IIIIII                     IIIIIIIII        IIIIIIIII                     IIIIII'.green
puts 'IIIIII                       IIIIIIIII    IIIIIIIII                       IIIIII'.green
puts 'IIIIII                         IIIIIIIIIIIIIIIIII                         IIIIII'.green
puts 'IIIIII                           IIIIIIIIIIIIII                           IIIIII'.green
puts 'IIIIII                             IIIIIIIIII                             IIIIII'.green
puts 'IIIIII                           IIIIIIIIIIIIII                           IIIIII'.green
puts 'IIIIII                         IIIIIIIIIIIIIIIIII                         IIIIII'.green
puts 'IIIIII                       IIIIIIIII    IIIIIIIII                       IIIIII'.green
puts 'IIIIII                     IIIIIIIII        IIIIIIIII                     IIIIII'.green
puts 'IIIIII                   IIIIIIIII            IIIIIIIII                   IIIIII'.green
puts 'IIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII                IIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII'.green
puts 'IIIIIIIIIIIIIIIIIIIIIIIIIIIIII                    IIIIIIIIIIIIIIIIIIIIIIIIIIIIII'.green
puts '  IIIIIIIIIIIIIIIIIIIIIIIIII                        IIIIIIIIIIIIIIIIIIIIIIIIII  '.green
puts ' '
puts '                       Bowtie. A Vagrant Box for Wordpress                      '
puts '                             by The Infinite Agency                             '
puts ' '

@dir_name = File.basename(Dir.getwd)

puts "What do you want to name this site? (#{@dir_name})".green << " Press return to use current name."
site_name = gets.chomp


if site_name == ''
  @project_name = @dir_name
else
  @project_name = site_name
end

puts '✨  Generating new project: '.green << @project_name

puts 'Connecting to Github'.green
  system("ssh -T git@github.com")
puts 'Pulling latest master of bowtie-wordpress on Github'.green
  system("rm -Rf www; git clone git@github.com:theinfiniteagency/bowtie-wordpress www")
puts 'Pulling latest master of bowtie on Github'.green
  system("git clone git@github.com:theinfiniteagency/bowtie www/wp-content/themes/bowtie")
puts "Updating Wordpress & Vagrant URL to #{@project_name}.dev".green
  system("sed -i '' 's/bowtie-vagrant/#{@project_name}/g' www/bowtie-wordpress.sql")
  system(%q!sed -i '' "/config.vm.hostname = /s/'\([^']*\)'/'! << @project_name << %q!'/" Vagrantfile!)
  system("sed -i '' 's/bowtie-vagrant/#{@project_name}/g' Vagrantfile")
  system("sed -i '' 's/bowtie-vagrant/#{@project_name}/g' www/wp-content/themes/bowtie/gulpfile.js")
puts "Database will be imported after the box has booted".magenta
puts "Starting Box".green
  system("vagrant up --provision");
