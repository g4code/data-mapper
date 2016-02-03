
include_recipe "apt"

package 'python-software-properties'

bash 'apt_ppa' do
  code <<-EOH
    sudo add-apt-repository ppa:ondrej/php5-5.6 -y
    sudo apt-get update
    EOH
end

package 'php5'

package 'php5-dev'

package 'apache2' do
  action :remove
end

mysql_service 'd' do
  port '3306'
  version '5.5'
  initial_root_password 'root'
  action [:create, :start]
end

mysql_client 'd' do
  action :create
end

package 'php5-mysql'

bash 'install_composer' do
  code <<-EOH
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
    EOH
  not_if { ::File.exists?("/usr/bin/composer") }
end
