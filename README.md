# CakePHP Apps plugin
CakePHP plugin to manage multiple apps / domains running on same APP and CORE codebase.

## Requirements
- This Plugin is written for Debian/Linux running Apache2 and MySQL

## Installation
- Install using Composer
- Install copying or cloning to Plugin/Apps

## Setup Debian
- Install sudo (as root) ```apt-get install sudo```
- Add Apache user (www-data) to sudoers ```sudo adduser www-data sudo```
- Allow Apache user (www-data) to passwordless execute the following commands (apachectl, a2ensite, a2dissite)
	- run ```visudo```
	- add ```www-data ALL = NOPASSWD: /usr/sbin/apachectl```
	- add ```www-data ALL = NOPASSWD: /usr/sbin/a2ensite```
	- add ```www-data ALL = NOPASSWD: /usr/sbin/a2dissite```
- Make sure {APACHE_DIR}/sites-available is writable by user running Apache (www-data)

## Setup
- Set database config for database Apps in Config/database.php
- Run ```cake schema create -p Apps```
- Grant the user of database Apps the following privileges: create, drop, create user, grant user, new databases
- Add all your document_roots at /apps/document_roots/
- Open /apps/applications/check_config in your browser and check for warnings
- create dir {APP}/Config/applcations for all your document_roots
- Append the following code to all document_root/{APP}/Config/bootstrap.php files

```php
if (isset($_SERVER['SERVER_NAME'])) {
	$bootstrap = APP . Configure::read('Apps.configDir') . DS . $_SERVER['SERVER_NAME'] . '.php';
} elseif (count($_SERVER['argv'])) {
	$bootstrap = APP . Configure::read('Apps.configDir') . DS . 'current_application';
}
if (file_exists($bootstrap)) {
	require($bootstrap);
}
```

- Add the following constructor method to all document_root/{APP}/Config/database.php DATABASE_CONFIG classes

```php
public function __construct () {
	$config = Configure::read('Database.config');
	if (is_array($config)) {
		$this->default = $config['default'];
	}
}
```

- Create your base schema.php and snapshots for each document_root and make sure the classname is AppSchema
- Create some apps at /apps/applications/add to see if everything is working fine

## ToDo
- files and webroot folders per application
- specific Apache redirects per application (redirect http://example.com/favicon to different files)
- Give Application.status titles instead of integers
- Composer + Packagist
- Travis CI (http://mark-story.com/posts/view/testing-cakephp-plugins-with-travis-ci)
- Slim down privileges of database Apps user
- Improve README
- Add more tests
