<?php
App::uses('Application', 'Apps.Model');
App::uses('File', 'Utility');

/**
 * Application Test Case
 *
 */
class IntegrationApplicationTest extends CakeTestCase {

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

	public function testCreateAndDelete() {
		// create
		$this->Application->create();
		$this->Application->saveAssociated(array('Application' => array('document_root_id' => 1), 'Database' => array('id' => ''), 'ServerAlias' => array(array('domain' => 'a.example.com'))));
		$this->Application->init();
		$id = $this->Application->id;
		$this->assertNotEmpty($this->Application->query("SHOW DATABASES LIKE 'application-$id'", false));
		$this->assertNotEmpty($this->Application->query("SELECT USER FROM mysql.user WHERE User='application-$id'", false));
		$file = new File(APP . Configure::read('Apps.configDir') . DS . 'application-' . $id . '.' . Configure::read('Apps.domain') . '.php');
		$this->assertTrue($file->exists());
		$fileServerAlias = new File(APP . Configure::read('Apps.configDir') . DS . 'a.example.com.php');
		$this->assertTrue($fileServerAlias->exists());
		$this->assertEquals($file->read(), $fileServerAlias->read());
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertTrue($file->exists());
		$content = $file->read();
		$this->assertTrue(strpos($content, 'ServerName application-' . $id . '.' . Configure::read('Apps.domain')) !== false);
		$this->assertTrue(strpos($content, 'ServerAlias a.example.com') !== false);

		// delete
		$this->Application->delete();
		$this->assertEmpty($this->Application->query("SHOW DATABASES LIKE 'application-$id'", false));
		$this->assertEmpty($this->Application->query("SELECT USER FROM mysql.user WHERE User='application-$id'", false));
		$file = new File(APP . Configure::read('Apps.configDir') . DS . 'application-' . $id . '.' . Configure::read('Apps.domain') . '.php');
		$this->assertFalse($file->exists());
		$fileServerAlias = new File(APP . Configure::read('Apps.configDir') . DS . 'a.example.com.php');
		$this->assertFalse($fileServerAlias->exists());
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertFalse($file->exists());
	}

	public function testDisable() {
		$application = $this->getMockForModel('Application', array('databaseCreate', 'writeConfig', 'restartApache'));
		$application->create();
		$application->saveAssociated(array('Application' => array('document_root_id' => 1), 'Database' => array('id' => '')));
		$application->init();
		$id = $application->id;
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertTrue($file->exists());
		$application->saveField('status', '0');
		$file = new File(Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . 'application-' . $id . '.' . Configure::read('Apps.domain'));
		$this->assertFalse($file->exists());
	}
}
