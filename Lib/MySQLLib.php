<?php
App::uses('Sanitize', 'Utility');
class MySQLLib {

	protected $db;

	public function __construct() {
		$this->db = ConnectionManager::getDataSource(Configure::read('Apps.dbConfig'));
	}

	public function createDatabase($name) {
		$name = Sanitize::escape($name);
		$sql = "CREATE DATABASE IF NOT EXISTS `$name`";
		return $this->db->rawQuery($sql);
	}

	public function dropDatabase($name) {
		$name = Sanitize::escape($name);
		$sql = "DROP DATABASE IF EXISTS `$name`";
		return $this->db->rawQuery($sql);
	}

	public function createSchema($serverName, $absolutePath = APP, $appDir = '') {
		$cakePath = Configure::read('Apps.cakePath');
		exec($cakePath . ' -app ' . APP . ' apps.current ' . $absolutePath . ' ' . $serverName);
		exec($cakePath . ' -app ' . $absolutePath . DS . $appDir . ' schema create --yes');
		exec($cakePath . ' -app ' . APP . ' apps.run updateschemacurrent ' . $absolutePath . ' ' . $appDir);
	}

	public function createUser($user, $password) {
		$user = Sanitize::escape($user);
		$password = Sanitize::escape($password);
		$sql = "CREATE USER '$user'@'localhost' IDENTIFIED BY '$password'";
		return $this->db->rawQuery($sql);
	}

	public function dropUser($user) {
		$user = Sanitize::escape($user);
		$sql = "DROP USER '$user'@'localhost'";
		$this->db->rawQuery($sql);
	}

	public function grantPrivileges($database, $user) {
		$database = Sanitize::escape($database);
		$user = Sanitize::escape($user);
		$sql = "GRANT ALL PRIVILEGES ON `$database` . * TO '$user'@'localhost'";
		$this->db->rawQuery($sql);
	}

	public function flushPrivileges() {
		$sql = 'FLUSH PRIVILEGES';
		$this->db->rawQuery($sql);
	}

	public function databaseExists($database) {
		$database = Sanitize::escape($database);
		$result = $this->db->fetchAll("SHOW DATABASES LIKE '$database'");
		return !empty($result);
	}

	public function dump($database, $filename) {
		$user = $this->db->config['login'];
		$password = $this->db->config['password'];
		exec('mysqldump -u' . $user . ' -p' . $password . ' ' . $database . ' > ' . Configure::read('Apps.dumpDir') . DS . $filename);
	}
}
