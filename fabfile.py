from fabric.api import *
from fabric.operations import put , get , local as localrun
from fabric.context_managers import lcd
from fabric.contrib.project import rsync_project
from subprocess import call

import os

env.wd = os.getcwd()

def qa(action='start'):
    if action=='start' :
        command= 'up '
    else:
        command=action

    localrun("cp %s/wp-config-local.php %s/wp-config.php " % (env.wd, env.wd) )
    localrun("SRCDIR=. DATADIR=.  HTTPPORT=80 RESTART=no docker-compose -p salons %s" % command )

#Deploy

if env.ssh_config_path and os.path.isfile(os.path.expanduser(env.ssh_config_path)):
    env.use_ssh_config = True
else:
    env.use_ssh_config = False

global blogs_def
blogs_def = {
    1 : { 'theme':'salons' , 'name':'salons'  ,'is_master' : True }
}
env.blogs = blogs_def.keys()
from config import preprod_env, prod_env , local_env
global preprod_env , prod_env
preprod_env = preprod_env
prod_env = prod_env


def set_config(env_config):

    for key in env_config:
        env[key] = env_config[key]
    env.blogs = blogs_def.keys()
    print env_config

def clean_mo_cache(dircache):
	env_run( 'rm -f %s/*.mo.php ' % dircache )

def wpk_notify(deploy_dir):
	print env.name
	destinataires = env.notify
	env_run ('mail -s "Deployement sur environnement %s" %s "Test" || echo "Cant notify"' % ( env.name , destinataires))



def wpk_deploy():
	env_run ('cd %s && git fetch && git log master..origin/%s > CHANGELOG.txt' % ( env.deploy_to, env.origin_branch ) )
	env_run ('cd %s && git pull -r origin %s:%s' % ( env.deploy_to, env.origin_branch, env.destination_branch ) )
	clean_mo_cache(env.cache_mo)

def wpk_deploy_theme():
    env_run ('cd %s && git fetch && git log master..origin/%s > CHANGELOG.txt' % ( env.deploy_theme_to, env.origin_branch ) )
    env_run ('cd %s && git pull -r origin %s:%s' % ( env.deploy_theme_to, env.origin_branch, env.destination_branch ) )
    clean_mo_cache(env.cache_mo)
    

def local():
    if 'local_env' in env:
        set_config(env.local_env)
    else:
		set_config(local_env)
def preprod():
	if 'preprod_env' in env:
		set_config(env.preprod_env)
	else:
		set_config(preprod_env)

def prod():
    if 'prod_env' in env:
        set_config(env.prod_env)
    else:
        set_config(prod_env)

def env_run(command):
    if env.name == 'local' or env.name == 'local_win' :
        localrun(command)
    elif env.name == 'docker' :
    	localrun("docker exec -ti %s sh -c '%s'" % ( env.container , command) ) 
    else:
        run(command) 

def send_file(file_name='REPORT.txt',file_dist=''):
	if file_dist =='':
		file_dist = file_name

	if 'project_path' in env:
		project_path = env.project_path
	else:
		project_path = env.deploy_to	

	put ( '%s/%s' %  ( env.wd, file_name ) , '%s/%s' %   ( project_path, file_dist )  )
	

def rm_file(file_dist):

	if 'project_path' in env:
		project_path = env.project_path
	else:
		project_path = env.deploy_to	

	env_run ('rm %s/%s' %( project_path, file_dist ))


def get_file(file_name,file_dist=''):


	if 'project_path' in env:
		project_path = env.project_path
	else:
		project_path = env.deploy_to

	if file_dist =='':
		file_dist = file_name
	get ( '%s/%s' %  ( project_path, file_dist ) , '%s/%s' % ( env.wd, file_name  )  )



def init_dirs():
	env_run ('cd %s && mkdir data scripts/crontab scripts/crontab/logs || echo "Already created"' % (env.deploy_to) )


def co():

	if 'project_path' in env:
		project_path = env.project_path
	else:
		project_path = env.deploy_to	

	env_run( 'cd %s  && git checkout . ' % ( project_path  ) )

#@roles('www')
def reload_nginx():
	global  prod_env
	if prod_env['name'] == 'foot365':
		env_run ('sudo systemctl reload nginx.service' )
	else :
		env_run ('sudo service nginx reload' )

#@roles('www')
def reload_php():

	env_run ('sudo service php7.3-fpm restart' )

def total_cache_cfg():
	env_run ('cp %s/wp-content/plugins/w3-total-cache/wp-content/advanced-cache.php %s/wp-content/advanced-cache.php' % ( env.deploy_to, env.deploy_to  ) )
	env_run( 'mkdir %s/wp-content/cache' % ( env.deploy_to  ) )
	env_run( 'chmod 777 %s/wp-content/cache' % ( env.deploy_to  ) )
	env_run( 'mkdir %s/wp-content/w3tc-config' % ( env.deploy_to  ) )
	env_run( 'chmod 777 %s/wp-content/w3tc-config' % ( env.deploy_to  ) )


# create symlink to git pre-commit config file 
def precommit():
	wd = os.getcwd()
	env_run('chmod a+x  %s/git_hooks/pre-commit' % (wd) )
	env_run( 'rm -f %s/.git/hooks/pre-commit '  % (  wd  )  )
	env_run( 'ln -s  %s/git_hooks/pre-commit %s/.git/hooks/pre-commit '  % (  wd , wd )  )
