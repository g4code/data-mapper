# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

    config.vm.box = "ubuntu/xenial64"

    config.vm.hostname = "data-mapper"

    config.vm.network "private_network", ip: "192.168.32.11"

    config.vm.synced_folder "./", "/vagrant_data"

    config.vm.provider "virtualbox" do |vb|
        vb.memory = 1024
        vb.cpus = 2
    end

    config.vm.provision "shell", inline: "test -e /usr/bin/python || (apt -y update && apt install -y python-minimal)"

    config.vm.provision "ansible" do |ansible|
        ansible.playbook = "ansible.yml"
    end
end
