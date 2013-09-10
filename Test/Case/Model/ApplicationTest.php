<?php
App::uses('Application', 'Apps.Model');
App::uses('File', 'Utility');

/**
 * Application Test Case
 *
 */
class ApplicationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.apps.application', 'plugin.apps.document_root', 'plugin.apps.database', 'plugin.apps.server_alias'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Application = ClassRegistry::init('Apps.Application');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Application);

		parent::tearDown();
	}

	public function testCleanEmptyServerAliases() {
		$data = array('Application' => array('server_name' => 'www.example2.com', 'document_root_id' => 1), 'ServerAlias' => array(array('domain' => 'example.com'), array(), array('domain' => '')));
		$cleanData = $this->Application->cleanEmptyServerAliases($data);
		$this->assertEqual(1, count($cleanData['ServerAlias']));
		$data = array('Application' => array('server_name' => 'www.example2.com', 'document_root_id' => 1), 'ServerAlias' => array(array('domain' => ''), array(), array('domain' => '')));
		$cleanData = $this->Application->cleanEmptyServerAliases($data);
		$this->assertFalse(array_key_exists('ServerAlias', $cleanData));
	}

	public function testValidateServerName() {
		$data = array('server_name' => 'example.com');
		$this->Application->set($data);
		$this->assertTrue($this->Application->validates());
		$data = array('server_name' => 'subdomain.example.com');
		$this->Application->set($data);
		$this->assertTrue($this->Application->validates());
		$data = array('server_name' => 'http://example.com');
		$this->Application->set($data);
		$this->assertFalse($this->Application->validates());
	}

	public function testAfterSave() {
		$domain = Configure::read('Apps.domain');
		$application = $this->getMockForModel('Apps.Application', array('apacheWriteDirective', 'databaseCreate', 'writeConfig', 'enableConfig', 'restartApache'));
		$application->create();
		$application->saveAssociated(array('Application' => array('document_root_id' => 1), 'Database' => array('id' => '')));
		$data = $application->read();
		$this->assertEqual('application-' . $data['Application']['id'] . '.' . $domain, $data['Application']['server_name']);
		$this->assertEqual('application-' . $data['Application']['id'], $data['Database']['database']);
	}

	public function testBeforeSaveServerName() {
		$application = $this->getMockForModel('Apps.Application', array('apacheWriteDirective', 'databaseCreate', 'writeConfig', 'enableConfig', 'restartApache'));
		$application->create();
		$application->save(array('server_name' => 'www.example2.com', 'document_root_id' => 1));
		$data = $application->read();
		$this->assertEqual('www.example2.com', $data['Application']['server_name']);
	}

	public function testInit() {
		$domain = Configure::read('Apps.domain');
		$application = $this->getMockForModel('Apps.Application', array('apacheWriteDirective', 'databaseCreate', 'writeConfig', 'enableConfig', 'restartApache'));
		$application->expects($this->once())
			->method('databaseCreate')
			->with('database_name', 'user_name');
		$application->create();
		$application->saveAssociated(array('Application' => array('document_root_id' => 1), 'Database' => array('database' => 'database_name', 'login' => 'user_name')));
		$application->init($application->id);
	}

	public function testServerAliases() {
		$application = $this->getMockForModel('Apps.Application', array('apacheWriteDirective', 'databaseCreate', 'writeConfig', 'enableConfig', 'restartApache', 'linkConfig'));
		$application->expects($this->exactly(2))
			->method('linkConfig');
		$data = $application->cleanEmptyServerAliases(array('Application' => array('server_name' => 'www.example2.com', 'document_root_id' => 1), 'ServerAlias' => array(array('domain' => 'example.com'), array('domain' => 'example3.com'), array('domain' => ''))));
		$application->create();
		$application->saveAssociated($data);
		$application->init($application->id);
		$data = $application->read();
		$this->assertEqual(2, count($data['ServerAlias']));
	}

	public function testAfterDelete() {
		$this->Application->create();
		$this->Application->saveAssociated(array('Application' => array('document_root_id' => 1), 'Database' => array('id' => '')));
		$id = $this->Application->id;
		$this->Application->init($id);
		$this->assertNotEmpty($this->Application->query("SHOW DATABASES LIKE 'application-$id'", false));
		$this->assertNotEmpty($this->Application->query("SELECT USER FROM mysql.user WHERE User='application-$id'", false));
		$file = new File(APP . Configure::read('Apps.configDir') . DS . 'application-' . $id . '.' . Configure::read('Apps.domain') . '.php');
		$this->assertTrue($file->exists());
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertTrue($file->exists());
		$this->Application->delete();
		$this->assertEmpty($this->Application->query("SHOW DATABASES LIKE 'application-$id'", false));
		$this->assertEmpty($this->Application->query("SELECT USER FROM mysql.user WHERE User='application-$id'", false));
		$file = new File(APP . Configure::read('Apps.configDir') . DS . 'application-' . $id . '.' . Configure::read('Apps.domain') . '.php');
		$this->assertFalse($file->exists());
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertFalse($file->exists());
	}

	public function testDisable() {
		$application = $this->getMockForModel('Application', array('databaseCreate', 'writeConfig', 'restartApache'));
		$application->create();
		$application->saveAssociated(array('Application' => array('document_root_id' => 1), 'Database' => array('id' => '')));
		$id = $application->id;
		$application->init($id);
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertTrue($file->exists());
		$application->saveField('status', '0');
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertFalse($file->exists());
	}
}
