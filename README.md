# bowtie-vagrant

This box will pull the bowtie-wordpress repo into the `www` folder and import the database during provision. Make sure the SSH key for your host system is saved in GitHub.

## In the Box
- Ubuntu 16.04
- Nginx 1.10
- PHP 7
- MySQL 5.7
- phpmyadmin
- [bowtie-wordpress](https://github.com/theinfiniteagency/bowtie-wordpress)
- [Bowtie](https://github.com/theinfiniteagency/bowtie)

## Quick Start

Run `$ ./provision.rb` for first boot and provisioning, or to destroy the current site and create a new one.

Access the site at `bowtie-vagrant.dev` or phpmyadmin at `bowtie-vagrant.dev:8080` (use your project name if you changed it during provision)

Optionally, install [Vagrant Hostsupdater](https://github.com/cogitatio/vagrant-hostsupdater) to automatically add the VM to your hosts file.

## Credentials

Account     | Username  | Password
------------|-----------|---------
MySQL       | root      | vagrant
WordPress   | coders    | B********
