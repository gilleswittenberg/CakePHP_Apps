<?php
App::uses('AppsAppModel', 'Apps.Model');
App::uses('Sanitize', 'Utility');
/**
 * Database Model
 *
 * @property Application $Application
 */
class Database extends AppsAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'application_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Application' => array(
			'className' => 'Apps.Application',
		)
	);

	public $password;

	public function afterSave($created) {
		if ($created) {
			$data = array();
			$applicationId = $this->field('application_id');
			if (empty($this->data['Database']['database'])) {
				$data['database'] = 'application-' . $applicationId;
			}
			if (empty($this->data['Database']['login'])) {
				$data['login'] = 'application-' . $applicationId;
			}
			if (!empty($data)) {
				$this->save($data);
			}
		}
	}

	public function beforeDelete($cascade = true) {
		$database = $this->find('first', array('conditions' => array('Database.id' => $this->id)));
		$this->dump($database['Database']['database']);
		return true;
	}

	public function createDatabase($name) {
		$name = Sanitize::escape($name);
		$sql = "CREATE DATABASE IF NOT EXISTS `$name`";
		return $this->query($sql, false);
	}

	public function dropDatabase($name) {
		$name = Sanitize::escape($name);
		$sql = "DROP DATABASE IF EXISTS `$name`";
		return $this->query($sql, false);
	}

	public function createSchema($serverName, $absolutePath = APP, $appDir = '') {
		$cakePath = Configure::read('Apps.cakePath') ?: 'Console' . DS . 'cake';
		exec(APP . $cakePath . ' -app ' . APP . ' apps.current ' . $absolutePath . ' ' . $serverName);
		exec(APP . $cakePath . ' -app ' . $absolutePath . DS . $appDir . ' schema create --yes');
		exec(APP . $cakePath . ' -app ' . APP . ' apps.run updateschemacurrent ' . $absolutePath . ' ' . $appDir);
	}

	public function createUser($user) {
		$user = Sanitize::escape($user);
		$password = $this->getPassword();
		$sql = "CREATE USER '$user'@'localhost' IDENTIFIED BY '$password'";
		$this->query($sql, false);
		return $password;
	}

	public function dropUser($user) {
		$user = Sanitize::escape($user);
		$sql = "DROP USER '$user'@'localhost'";
		$this->query($sql, false);
	}

	public function grantPrivileges($database, $user) {
		$sql = "GRANT ALL PRIVILEGES ON `$database` . * TO '$user'@'localhost'";
		$this->query($sql, false);
	}

	public function flushPrivileges() {
		$sql = 'FLUSH PRIVILEGES';
		$this->query($sql, false);
	}

	public function dump($database, $filename = null) {
		if (!$this->databaseExists($database)) {
			return false;
		}
		if (!$filename) {
			$filename = $database . '_' . date('m-d-Y-His') . '.sql';
		}
		$dirname = Configure::read('Apps.dumpDir') ?: APP . 'files';
		$dataSource = ConnectionManager::getDataSource(Configure::read('Apps.dbConfig'));
		$user = $dataSource->config['login'];
		$password = $dataSource->config['password'];
		exec('mysqldump -u' . $user . ' -p' . $password . ' ' . $database . ' > ' . $dirname . DS . $filename);
	}

	public function databaseExists($database) {
		$result = $this->query("SHOW DATABASES LIKE '$database'");
		return !empty($result);
	}

	protected function getPassword($length = 12) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$this->password = substr(str_shuffle($chars), 0, $length);
		return $this->password;
	}
}
