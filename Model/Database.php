<?php
App::uses('AppsAppModel', 'Apps.Model');
App::uses('Sanitize', 'Utility');
App::uses('MySQLLib', 'Apps.Lib');
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
	protected $MySQLLib;

	public function afterSave($created, $options = array()) {
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
		$this->initMySQLLib();
		return $this->MySQLLib->createDatabase($name);
	}

	public function dropDatabase($name) {
		$this->initMySQLLib();
		return $this->MySQLLib->dropDatabase($name);
	}

	public function createSchema($serverName, $absolutePath = APP, $appDir = '') {
		$this->initMySQLLib();
		$this->MySQLLib->createSchema($serverName, $absolutePath, $appDir);
	}

	public function createUser($user) {
		$password = $this->getPassword();
		$this->initMySQLLib();
		return $this->MySQLLib->createUser($user, $password);
	}

	public function dropUser($user) {
		$this->initMySQLLib();
		return $this->MySQLLib->dropUser($user);
	}

	public function grantPrivileges($database, $user) {
		$this->initMySQLLib();
		return $this->MySQLLib->grantPrivileges($database, $user);
	}

	public function flushPrivileges() {
		$this->initMySQLLib();
		return $this->MySQLLib->flushPrivileges();
	}

	public function dump($database, $filename = null) {
		if (!$this->databaseExists($database)) {
			return false;
		}
		if (!$filename) {
			$filename = $database . '_' . date('m-d-Y-His') . '.sql';
		}
		$this->initMySQLLib();
		$this->MySQLLib->dump($database, $filename);
	}

	public function databaseExists($database) {
		$this->initMySQLLib();
		return $this->MySQLLib->databaseExists($database);
	}

	protected function initMySQLLib() {
		if (is_null($this->MySQLLib)) {
			$this->MySQLLib = new MySQLLib();
		}
	}

	protected function getPassword($length = 12) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$this->password = substr(str_shuffle($chars), 0, $length);
		return $this->password;
	}
}
