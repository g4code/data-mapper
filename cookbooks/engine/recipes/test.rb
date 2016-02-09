
bash 'import_database' do
  code <<-EOH
    mysql -h 127.0.0.1 -u root -proot -e "CREATE DATABASE IF NOT EXISTS data_mapper DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;"
    mysql -h 127.0.0.1 -u root -proot data_mapper < /vagrant_data/cookbooks/engine/data/dump.sql
    EOH
end