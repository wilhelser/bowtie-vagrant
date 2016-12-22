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
puts ' IIIIIIIIIIIIIIIIIIIIIIIIIII                        IIIIIIIIIIIIIIIIIIIIIIIIIII '.green
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
puts ' IIIIIIIIIIIIIIIIIIIIIIIIIII                        IIIIIIIIIIIIIIIIIIIIIIIIII '.green
puts ' '

puts ' '
def say_hello
    puts "What do you want to call this site? (ex. bowtie-vagrant)".green
    site_name = gets.chomp
end

@project_name = say_hello
puts 'Connecting to Github'.green
system("ssh -T git@github.com")
puts 'Pulling latest version of Bowtie Wordpress on Github'.green
system("rm -Rf www; git clone git@github.com:theinfiniteagency/bowtie-wordpress www")
puts "Updating Wordpress & Vagrant URL to #{@project_name}.dev".green
system("sed -i '' 's/infinitedev/#{@project_name}/g' www/bowtie-wordpress.sql")
system("sed -i '' 's/bowtie-vagrant/#{@project_name}/g' Vagrantfile")
puts "Database will be imported after the box has booted".magenta
puts "Starting Box".green
system("vagrant up --provision");
