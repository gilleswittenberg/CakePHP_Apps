<?php
App::uses('ServerAlias', 'Apps.Model');

/**
 * ServerAlias Test Case
 *
 */
class ServerAliasTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.apps.server_alias',
		'plugin.apps.application',
		'plugin.apps.document_root',
		'plugin.apps.database',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ServerAlias = ClassRegistry::init('Apps.ServerAlias');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ServerAlias);

		parent::tearDown();
	}

	public function testValidDomain() {
		$this->ServerAlias->set(array('domain' => 'invalid_domain'));
		$this->assertFalse($this->ServerAlias->validates());
	}

	/*
	public function testCreateLink() {
		$this->ServerAlias->save(array('application_id' => 1, 'domain' => 'www.example.com'));
		$this->assertNotEmpty(exec('find -H ' . APP . Configure::read('Apps.configDir') . DS . 'www.example.com.php'));
		// clean up
		exec('unlink ' . APP . Configure::read('Apps.configDir') . DS . 'www.example.com.php');
	}

	public function testDeleteLink() {
		$this->ServerAlias->save(array('application_id' => 1, 'domain' => 'www.example.com'));
		$this->assertNotEmpty(exec('find -H ' . APP . Configure::read('Apps.configDir') . DS . 'www.example.com.php'));
		$this->ServerAlias->delete();
		$this->assertEmpty(exec('find -H ' . APP . Configure::read('Apps.configDir') . DS . 'www.example.com.php'));
	}
	*/
}
