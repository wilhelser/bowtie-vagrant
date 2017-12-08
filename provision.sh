#!/bin/sh
sed -i 's/^mesg n$/tty -s \&\& mesg n/g' /root/.profile
echo 'Importing Bowtie DB'
mysql --login-path=local -e "DROP DATABASE IF EXISTS wordpress"
mysql --login-path=local -e "CREATE DATABASE wordpress"
mysql --login-path=local wordpress < /var/www/bowtie-wordpress.sql
rm -f /var/www/bowtie-wordpress.sql
echo "Generating self-signed SSL certificate"
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048  -subj "/CN=$1.localhost" -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt
echo "Generating strong Diffie-Hellman group"
sudo openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048
echo "Restarting NGINX"
sudo systemctl restart nginx
echo "🎉  Now serving Wordpress on $1.localhost"
echo "🗄  Go to :8080 to manage the DB"