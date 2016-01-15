execute 'apt_get_update' do
  command 'apt-get update'
end

package 'make'

package 'git'

package 'curl'