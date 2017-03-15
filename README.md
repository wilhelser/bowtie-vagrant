# bowtie-vagrant

A pre-configured Ubuntu LEMP box for Wordpress.

Make sure the SSH key for your host system is saved in GitHub to use the provision script. It will pull the `Bowtie` & `bowtie-wordpress` repos into `www` and import the `www/bowtie-wordpress.sql` database during provision.

## In the Box
- Ubuntu 16.04
- Nginx 1.10
- PHP 7
- MySQL 5.7
- phpMyAdmin 4.5.4

#### External Dependencies

- [bowtie-wordpress](https://github.com/theinfiniteagency/bowtie-wordpress)
- [Bowtie](https://github.com/theinfiniteagency/bowtie)
- Optionally, install [Vagrant Hostsupdater](https://github.com/cogitatio/vagrant-hostsupdater) to automatically add the VM to your hosts file.

## Quick Start

**Using Bowtie CLI:** Run `$ bowtie new project_name` to create and provision.

**Using Provision Script:** Clone the repo and run `$ ./provision.rb` for first boot and provisioning, or to destroy the current site and create a new one.

Access the site at `bowtie-vagrant.dev` or phpmyadmin at `bowtie-vagrant.dev:8080` (use your project name if you changed it during provision)

## Credentials

Account     | Username  | Password
------------|-----------|---------
MySQL       | root      | vagrant
WordPress   | coders    | B********

## Destroying with a Backup

To save space (each box is about 500mb), you can destroy the VM while keeping a copy of the site and db, ready for next boot.

**Using Bowtie CLI**

1. Run `$ bowtie backup -d` to dump the db to www and destroy the machine (remove the -d flag to only create a backup)
3. Use `$ bowtie up` to restore the site.

**Manually**

1. First, login to phpmyadmin and dump the `wordpress` db to `bowtie-wordpress.sql` in the `www` folder.
2. Run `$ vagrant destroy` to delete the VM
3. When you are ready to use the site again, run `$ vagrant up --provision`. This will rebuild the box and import the db again.

**Remember, running the provision.rb script will overwrite your site files and db with a new install.**

## Repackaging Box for Atlas

```
$ vagrant ssh
# Make your updates inside the box then:
$ sudo apt-get clean
$ sudo dd if=/dev/zero of=/EMPTY bs=1M
$ sudo rm -f /EMPTY
$ cat /dev/null > ~/.bash_history && history -c && exit
$ vagrant package --output bowtie.box
```

Login to [Hashicorp Atlas](https://atlas.hashicorp.com/) and upload a new version for the virtualbox provider.
