<?php
App::uses('File', 'Utility');
App::uses('ApacheLib', 'Apps.Lib');
/**
 * Application Model
 *
 * @property DocumentRoot $DocumentRoot
 * @property Database $Database
 * @property ServerAlias $ServerAlias
 */
class Application extends AppsAppModel {

	protected $deletedRow;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'document_root_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'slug' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
			'unique' => array(
				'rule' => 'isUnique'
			)
		),
		'status' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'DocumentRoot' => array(
			'className' => 'Apps.DocumentRoot'
		)
	);

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'Database' => array(
			'className' => 'Apps.Database'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ServerAlias' => array(
			'className' => 'Apps.ServerAlias'
		)
	);

	public function cleanEmptyServerAliases($data) {
		if (!empty($data['ServerAlias'])) {
			foreach ($data['ServerAlias'] as $key => $value) {
				if (empty($value['domain'])) {
					unset($data['ServerAlias'][$key]);
				}
			}
		}
		if (empty($data['ServerAlias'])) {
			unset($data['ServerAlias']);
		}
		return $data;
	}

	public function init($id) {
		$application = $this->find('first', array('conditions' => array('Application.id' => $id)));
		$absolutePath = $application['DocumentRoot']['absolute_path'];
		$appDir = $application['DocumentRoot']['app_dir'];
		$serverName = $application['Application']['server_name'];
		$applicationId = $application['Application']['slug'];
		$databaseName = $application['Database']['database'];
		$databaseLogin = $application['Database']['login'];
		// create directive
		$this->apacheWriteDirective($id);
		// create database
		$password = $this->databaseCreate($databaseName, $databaseLogin, $absolutePath);
		// write CakePHP config
		$this->writeConfig($absolutePath, $serverName, $applicationId, $applicationId, $password);
		// link config for ServerAliases
		foreach ($application['ServerAlias'] as $serverAlias) {
			$this->linkConfig($absolutePath, $serverName, $serverAlias['domain']);
		}
		// init tables and rows
		$this->Database->createSchema($serverName, $absolutePath, $appDir);
		// restart Apache
		$this->restartApache();
	}

	public function afterSave($created) {
		if ($created) {
			$applicationId = 'application-' . $this->id;
			$data = array('slug' => $applicationId);
			if (empty($this->data['Application']['server_name'])) {
				$data['server_name'] = $applicationId . '.' . Configure::read('Apps.domain');
			}
			$this->save($data);
		} else {
			if ((isset($this->data['Application']['status']) && (string)$this->data['Application']['status'] === '0')) {
				$this->apacheDeleteDirective();
			}
		}
	}

	public function beforeDelete ($cascade = true) {
		$this->deletedRow = $this->find('first', array('conditions' => array('Application.id' => $this->id)));
		return true;
	}

	public function afterDelete() {
		$this->Database->dump($this->deletedRow['Database']['database']);
		$this->Database->dropDatabase($this->deletedRow['Database']['database']);
		$this->Database->dropUser($this->deletedRow['Database']['login']);
		$this->deleteConfig($this->deletedRow['DocumentRoot']['absolute_path'], $this->deletedRow['Application']['server_name']);
		$apacheLib = new ApacheLib();
		$apacheLib->disableDirective($this->deletedRow['Application']['server_name']);
		$apacheLib->deleteDirective($this->deletedRow['Application']['server_name']);
		$apacheLib->restart();
	}

	// extracted function to easily mock out in tests
	protected function apacheWriteDirective($id) {
		$application = $this->find('first', array('conditions' => array('Application.id' => $id)));
		$apacheLib = new ApacheLib();
		$apacheLib->writeDirective($application['Application']['server_name'], $application['DocumentRoot']['absolute_path'], $application['ServerAlias']);
		$apacheLib->enableDirective($application['Application']['server_name']);
	}

	// extracted function to easily mock out in tests
	protected function apacheDeleteDirective() {
		$application = $this->find('first', array('conditions' => array('Application.id' => $this->id)));
		$apacheLib = new ApacheLib();
		$apacheLib->deleteDirective($application['Application']['server_name']);
	}

	// extracted function to easily mock out in tests
	protected function databaseCreate($database, $user, $path) {
		if (!$this->Database->databaseExists($database)) {
			$this->Database->createDatabase($database);
			//$this->Database->createTables($database, $path);
			//$this->Database->initTables($database, $path);
		}
		$password = $this->Database->createUser($user);
		$this->Database->grantPrivileges($database, $user);
		return $password;
	}

	/*
	protected function databaseInit($absolutePath, $serverName) {
		$cakePath = Configure::read('Apps.cakePath') ?: 'Console' . DS . 'cake';
		exec($absolutePath . DS . $cakePath . ' apps.current ' . $serverName);
		exec($absolutePath . DS . $cakePath . ' schema create');
	}
	*/

	// http://bakery.cakephp.org/articles/eimermusic/2009/02/18/one-core-one-app-multiple-domains
	protected function writeConfig($documentRoot, $serverName, $database, $login, $password) {
		$file = new File($documentRoot . DS . Configure::read('Apps.configDir') . DS . $serverName . '.php', true);
		$content = "<?php
Configure::write('Database.config', array(
	'default' => array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => '$login',
		'password' => '$password',
		'database' => '$database',
		'schema' => '',
		'prefix' => '',
		'encoding' => 'utf8'
	)
));
";
		$file->write($content);
	}

	protected function deleteConfig($documentRoot, $serverName) {
		$file = new File($documentRoot . DS . Configure::read('Apps.configDir') . DS . $serverName . '.php', true);
		$file->delete();
	}

	public function linkConfig($documentRoot, $serverName, $serverAlias) {
		$target = $documentRoot . DS . Configure::read('Apps.configDir') . DS . $serverName . '.php';
		$symbolic = $documentRoot . DS . Configure::read('Apps.configDir') . DS . $serverAlias . '.php';
		exec("ln -s $target $symbolic");
	}

	public function unlinkConfig($documentRoot, $serverAlias) {
		$symbolic = $documentRoot . DS . Configure::read('Apps.configDir') . DS . $serverAlias . '.php';
		exec("unlink $symbolic");
	}

	protected function restartApache() {
		$apacheLib = new ApacheLib();
		$apacheLib->restart();
	}
}
