set :application, "lms"
set :user,        "root"
set :domain,      "mmsch.teiath.gr"
set :deploy_to,   "/var/www/lms"
set :app_path,    "app"
ssh_options[:port] = "599"

set :repository,  "git@github.com:teiath/lms.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :use_sudo, false
set  :use_composer, true
set  :update_vendors, true
set  :dump_assetic_assets, true
set  :interactive_mode, false
set  :keep_releases,  3

set :shared_files, ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs", web_path + "/upload", web_path + "/cache"]

set :writable_dirs, [app_path + "/logs", app_path + "/cache"]
set :webserver_user,    "apache"
set :permission_method, :acl
set :use_set_permissions, true

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

# Hooks
before "deploy:restart", "deploy:set_permissions"
after  "symfony:assetic:dump", "symfony:doctrine:schema:update" # Update doctrine schema
after  "symfony:assetic:dump", "symfony:update_admin_acl"

namespace :symfony do
  desc "Runs the test suite"
  task :run_tests, :roles => :app, :except => { :no_release => true } do
    capifony_pretty_print "--> Clearing test environment cache"
    run "#{try_sudo} sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} cache:clear --env=test --no-debug'"
    capifony_puts_ok
    capifony_pretty_print "--> Running test suite"
    run "#{try_sudo} sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} doctrine:schema:update --force --env=test'"
    run "#{try_sudo} sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} doctrine:fixtures:load --env=test --no-interaction'"
    run "#{try_sudo} sh -c 'cd #{latest_release} && phpunit -c #{app_path} src'"
    capifony_puts_ok
  end
  desc "Import locale"
  task :import_locale, :roles => :app, :except => { :no_release => true } do
    capifony_pretty_print "--> Importing translation files"
    run "#{try_sudo} sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} locale:editor:import --env=#{symfony_env_prod}'"
    capifony_puts_ok
  end
  desc "Update admin acl"
  task :update_admin_acl, :roles => :app, :except => { :no_release => true } do
    capifony_pretty_print "--> Updating admin ACL"
    run "#{try_sudo} sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} sonata:admin:setup-acl --env=#{symfony_env_prod}'"
    capifony_puts_ok
  end
  task :update_acl, :roles => :app do
  end
  task :apc, :roles => :app do
  end
end