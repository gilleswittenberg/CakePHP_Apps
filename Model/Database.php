<?php
App::uses('AppsAppModel', 'Apps.Model');
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
			$application = $this->Application->find('first', array('conditions' => array('Application.id' => $applicationId)));
			if ($application) {
				if (empty($this->data['Database']['database'])) {
					$this->saveField('database', $application['Application']['slug']);
				}
				if (empty($this->data['Database']['login'])) {
					$this->saveField('login', $application['Application']['slug']);
				}
			}
		}
	}

	public function beforeDelete($cascade = true) {
		$database = $this->find('first', array('conditions' => array('Database.id' => $this->id)));
		$this->dump($database['Database']['database']);
		return true;
	}

	public function createDatabase($name) {
		$sql = "CREATE DATABASE IF NOT EXISTS `$name`";
		return $this->query($sql, false);
	}

	public function dropDatabase($name) {
		$sql = "DROP DATABASE IF EXISTS `$name`";
		return $this->query($sql, false);
	}

	public function createSchema($serverName, $absolutePath = APP) {
		$cakePath = Configure::read('Apps.cakePath') ?: 'Console' . DS . 'cake';
		exec($absolutePath . DS . $cakePath . ' -app ' . APP . ' apps.current ' . $serverName);
		exec($absolutePath . DS . $cakePath . ' -app ' . $absolutePath . ' schema create --yes');
		exec($absolutePath . DS . $cakePath . ' -app ' . $absolutePath . ' apps.run updateschemacurrent ' . $absolutePath);
	}
	/*
	public function createTables($name, $absolutePath = APP) {
		$dbConfig = Configure::read('Apps.dbConfig') ?: 'default';
		$dataSource = ConnectionManager::getDataSource($dbConfig);
		$database = $dataSource->config['database'];
		$file = new File($absolutePath . DS . Configure::read('Apps.sqlDir') . DS . Configure::read('Apps.schemaFile'));
		if ($file->exists()) {
			$sql = "USE `$name`;";
			$sql .= $file->read();
			$sql .= "USE `$database`;";
			$this->query($sql, false);
		}
	}

	public function initTables($name, $absolutePath = APP) {
		$dbConfig = Configure::read('Apps.dbConfig') ?: 'default';
		$dataSource = ConnectionManager::getDataSource($dbConfig);
		$database = $dataSource->config['database'];
		$file = new File($absolutePath . DS . Configure::read('Apps.sqlDir') . DS . Configure::read('Apps.tablesFile'));
		if ($file->exists()) {
			$sql = "USE `$name`;";
			$sql .= $file->read();
			$sql .= "USE `$database`;";
			$this->query($sql, false);
		}
	}
	*/

	public function createUser($user) {
		$password = $this->getPassword();
		$sql = "CREATE USER '$user'@'localhost' IDENTIFIED BY '$password'";
		$this->query($sql, false);
		return $password;
	}

	public function dropUser($user) {
		$sql = "DROP USER '$user'@'localhost'";
		$this->query($sql, false);
	}

	public function grantPrivileges($database, $user) {
		$sql = "GRANT ALL PRIVILEGES ON `$database` . * TO '$user'@'localhost'";
		$this->query($sql, false);
	}

	public function flushPrivilges() {
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
