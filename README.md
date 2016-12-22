# bowtie-vagrant

This box will pull the bowtie-wordpress repo into the `www` folder and import the database during provision. Make sure the SSH key for your host system is saved in GitHub.

## In the Box
- Ubuntu 16.04
- Nginx 1.10
- PHP 7
- MySQL 5.7
- [bowtie-wordpress](/theinfiniteagency/bowtie-wordpress)


## Quick Start

Run `$ ./provision.sh` for first boot and provisioning, or to destroy the current site and create a new one.

Optionally, install [Vagrant Hostsupdater](https://github.com/cogitatio/vagrant-hostsupdater) to automatically add the VM to your hosts file.

## Credentials

Account     | Username  | Password
------------|-----------|---------
MySQL       | root      | vagrant
WordPress   | coders    | B********
