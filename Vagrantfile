# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  # All Vagrant configuration is done here. The most common configuration
  # options are documented and commented below. For a complete reference,
  # please see the online documentation at vagrantup.com.

  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = 'bowtie'

  # The hostname for the VM
  config.vm.hostname = 'bowtie-wordpress'

  config.ssh.insert_key = false

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  # config.vm.box_check_update = false

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network 'private_network', ip: '192.168.56.26'

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # If true, then any SSH connections made will enable agent forwarding.
  # Default value: false
  config.ssh.forward_agent = true

  # Create an entry in the /etc/hosts file for #{hostname}.dev
  if defined? VagrantPlugins::HostsUpdater
    config.hostsupdater.aliases = ["#{config.vm.hostname}.dev"]
  end

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder "../data", "/vagrant_data"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  # config.vm.provider "virtualbox" do |vb|
  #   # Don't boot with headless mode
  #   vb.gui = true
  #
  #   # Use VBoxManage to customize the VM. For example to change memory:
  #   vb.customize ["modifyvm", :id, "--memory", "1024"]
  # end
  config.vm.provider 'virtualbox' do |vb|
    vb.customize ['modifyvm', :id, '--memory', '1024']
  end

  config.vm.synced_folder 'www', '/www', id: 'vagrant-root',
    owner: 'vagrant',
    group: 'www-data',
    mount_options: ["dmode=774,fmode=775"]

  # View the documentation for the provider you're using for more
  # information on available options.

  $clone = <<-SHELL
    echo 'Starting Wordpress Setup'
    mkdir -p ~/.ssh
    chmod 700 ~/.ssh
    ssh-keyscan -H github.com >> ~/.ssh/known_hosts
    ssh -T git@github.com
    git clone git@github.com:theinfiniteagency/bowtie-wordpress /www-temp
    mv /www-temp/* /www/
    rm -Rf /www-temp
    sed -i "s/bowtie-wordpress/$1/g" /www/bowtie-wordpress.sql
    echo 'Importing Bowtie Database'
    mysql -uroot -pvagrant -e "drop database wordpress"
    mysql -uroot -pvagrant -e "create database wordpress"
    mysql wordpress -uroot -pvagrant < /www/bowtie-wordpress.sql
    rm /www/bowtie-wordpress.sql
  SHELL

  config.vm.provision "shell", args: "#{config.vm.hostname}", inline: $clone
end
