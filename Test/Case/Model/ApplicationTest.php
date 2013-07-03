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

	public function testBeforeSave() {
		$domain = Configure::read('Apps.domain');
		$application = $this->getMockForModel('Apps.Application', array('apacheWriteDirective', 'databaseCreate', 'writeConfig', 'enableConfig', 'restartApache'));
		$application->create();
		$application->save(array('document_root_id' => 1));
		$data = $application->read();
		$this->assertEqual('application-' . $data['Application']['id'] . '.' . $domain, $data['Application']['server_name']);
	}

	public function testBeforeSaveServerName() {
		$application = $this->getMockForModel('Apps.Application', array('apacheWriteDirective', 'databaseCreate', 'writeConfig', 'enableConfig', 'restartApache'));
		$application->create();
		$application->save(array('server_name' => 'www.example2.com', 'document_root_id' => 1));
		$data = $application->read();
		$this->assertEqual('www.example2.com', $data['Application']['server_name']);
	}

	public function testBeforeSaveDatabaseName() {
		$domain = Configure::read('Apps.domain');
		$application = $this->getMockForModel('Apps.Application', array('apacheWriteDirective', 'databaseCreate', 'writeConfig', 'enableConfig', 'restartApache'));
		$application->expects($this->once())
			->method('databaseCreate')
			->with('database_name', 'user_name');
		$application->create();
		$application->save(array('Application' => array('document_root_id' => 1), 'Database' => array('database' => 'database_name', 'login' => 'user_name')));
	}

	public function testAfterDelete() {
		$this->Application->create();
		$this->Application->save(array('document_root_id' => 1));
		$id = $this->Application->id;
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
		$application->save(array('document_root_id' => 1));
		$id = $application->id;
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertTrue($file->exists());
		$application->saveField('status', 0);
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertFalse($file->exists());
	}
}
