set :application, "psdtg"
set :user,        "lms"
set :domain,      "lms.dnna.gr"
set :deploy_to,   "/home/lms/capifony"
set :app_path,    "app"

set :repository,  "git@github.com:dnna/psdtg.git"
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

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL