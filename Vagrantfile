# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

    config.vm.box = "hashicorp/precise64"

    config.vm.hostname = "data-mapper"

    config.vm.network "private_network", ip: "192.168.32.11"

    config.vm.synced_folder "./", "/vagrant_data"

    config.vm.provider "virtualbox" do |vb|
        vb.memory = 1024
        vb.cpus = 2
    end

    config.vm.provision "basics", type: "chef_solo" do |chef|
        chef.add_recipe "basics"
    end
end
