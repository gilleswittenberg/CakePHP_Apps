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

	public function afterSave($created) {
		if ($created) {
			// application id string
			$applicationId = 'application-' . $this->id;
			// database fields
			$databaseName = !empty($this->data['Database']['database']) ? $this->data['Database']['database'] : $applicationId;
			$databaseLogin = !empty($this->data['Database']['login']) ? $this->data['Database']['login'] : $applicationId;
			//$databasePassword = !empty($this->data['Database']['password']) ? $this->data['Database']['password'] : null;
			// save servername
			if (empty($this->data['Application']['server_name'])) {
				$serverName = $applicationId . '.' . Configure::read('Apps.domain');
				$this->saveField('server_name', $serverName);
			} else {
				$serverName = $this->data['Application']['server_name'];
			}
			// save slug
			$this->saveField('slug', $applicationId);
			// create directive
			$this->apacheWriteDirective();
			// read absolute_path
			$application = $this->find('first', array('conditions' => array('Application.id' => $this->id)));
			// create database
			$password = $this->databaseCreate($databaseName, $databaseLogin, $application['DocumentRoot']['absolute_path']);
			// write CakePHP config
			$this->writeConfig($application['DocumentRoot']['absolute_path'], $serverName, $applicationId, $applicationId, $password);
			// init tables and rows
			$this->Database->createSchema($serverName, $application['DocumentRoot']['absolute_path']);
			// restart Apache
			$this->restartApache();
		} else {
			$application = $this->find('first', array('conditions' => array('Application.id' => $this->id)));
			if (!$application['Application']['status']) {
				$this->apacheDeleteDirective($application['Application']['server_name']);
			}
		}
	}

	public function beforeDelete ($cascade = true) {
		$this->deletedRow = $this->find('first', array('conditions' => array('Application.id' => $this->id)));
		return true;
	}

	public function afterDelete() {
		$this->Database->dump($this->deletedRow['Application']['slug']);
		$this->Database->dropDatabase($this->deletedRow['Application']['slug']);
		$this->Database->dropUser($this->deletedRow['Application']['slug']);
		$this->deleteConfig($this->deletedRow['DocumentRoot']['absolute_path'], $this->deletedRow['Application']['server_name']);
		$apacheLib = new ApacheLib();
		$apacheLib->disableDirective($this->deletedRow['Application']['server_name']);
		$apacheLib->deleteDirective($this->deletedRow['Application']['server_name']);
		$apacheLib->restart();
	}

	// extracted function to easily mock out in tests
	protected function apacheWriteDirective() {
		$application = $this->find('first', array('conditions' => array('Application.id' => $this->id)));
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

	protected function databaseInit($absolutePath, $serverName) {
		$cakePath = Configure::read('Apps.cakePath') ?: 'Console' . DS . 'cake';
		exec($absolutePath . DS . $cakePath . ' apps.current ' . $serverName);
		exec($absolutePath . DS . $cakePath . ' schema create');
	}

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

	public function linkConfig ($documentRoot, $serverName, $serverAlias) {
		$target = $documentRoot . DS . Configure::read('Apps.configDir') . DS . $serverName . '.php';
		$symbolic = $documentRoot . DS . Configure::read('Apps.configDir') . DS . $serverAlias . '.php';
		exec("ln -s $target $symbolic");
	}

	public function unlinkConfig ($documentRoot, $serverAlias) {
		$symbolic = $documentRoot . DS . Configure::read('Apps.configDir') . DS . $serverAlias . '.php';
		exec("unlink $symbolic");
	}

	protected function restartApache() {
		$apacheLib = new ApacheLib();
		$apacheLib->restart();
	}
}
