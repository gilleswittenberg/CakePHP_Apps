<?php
App::uses('Application', 'Apps.Model');
App::uses('Database', 'Apps.Model');
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
		$this->assertEqual('application-' . $data['Application']['id'], $data['Database']['login']);
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
		$application->Database = $this->getMockForModel('Apps.Database', array('createSchema'));
		$application->expects($this->once())
			->method('apacheWriteDirective');
		$application->expects($this->once())
			->method('databaseCreate')
			->with('database_name', 'user_name');
		$application->expects($this->once())
			->method('writeConfig');
		$application->Database->expects($this->once())
			->method('createSchema');
		$application->create();
		$application->saveAssociated(array('Application' => array('document_root_id' => 1), 'Database' => array('database' => 'database_name', 'login' => 'user_name')));
		$application->init();
	}

	public function testServerAliases() {
		$application = $this->getMockForModel('Apps.Application', array('apacheWriteDirective', 'databaseCreate', 'writeConfig', 'enableConfig', 'restartApache', 'linkConfig'));
		$application->expects($this->once())
			->method('apacheWriteDirective');
		$application->expects($this->exactly(2))
			->method('linkConfig');
		$application->Database = $this->getMockForModel('Apps.Database', array('createSchema'));
		$data = $application->cleanEmptyServerAliases(array('Application' => array('server_name' => 'www.example2.com', 'document_root_id' => 1), 'ServerAlias' => array(array('domain' => 'example.com'), array('domain' => 'example3.com'), array('domain' => ''))));
		$application->create();
		$application->saveAssociated($data);
		$application->init();
		$data = $application->read();
		$this->assertEqual(2, count($data['ServerAlias']));
	}
}
