
mysql_service 'd' do
  port '3306'
  version '5.5'
  initial_root_password 'root'
  action [:create, :start]
end

mysql_client 'd' do
  action :create
end

bash 'grant_privileges' do
  code <<-EOH
    mysql -h 127.0.0.1 -u root -proot -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'root';"
    mysql -h 127.0.0.1 -u root -proot -e "FLUSH PRIVILEGES;"
    mysql -h 127.0.0.1 -u root -proot -e "CREATE DATABASE IF NOT EXISTS data_mapper DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;"
    mysql -h 127.0.0.1 -u root -proot data_mapper < /vagrant_data/cookbooks/engine/data/dump.sql
    EOH
end