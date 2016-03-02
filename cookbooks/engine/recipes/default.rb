
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
    EOH
end

node.override[:java][:openjdk_packages] = ["openjdk-7-jdk"]
include_recipe 'java'

elasticsearch_user 'elasticsearch' do
  action :nothing
end

elasticsearch_install 'elasticsearch' do
  type :package
end

elasticsearch_configure 'elasticsearch' do
    allocated_memory '256m'
    configuration ({
      'cluster.name' => 'data_mapper',
      'node.name' => 'node01'
    })
end

elasticsearch_service 'elasticsearch' do
  service_actions [:enable, :start]
end
