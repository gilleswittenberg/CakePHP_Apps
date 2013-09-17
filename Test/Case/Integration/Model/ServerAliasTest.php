<?php
App::uses('ServerAlias', 'Apps.Model');

/**
 * ServerAlias Test Case
 *
 */
class IntegrationServerAliasTest extends CakeTestCase {

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

	public function testCreateLink() {
		$this->ServerAlias->save(array('application_id' => 1, 'domain' => 'www.example.com'));
		$this->ServerAlias->add();
		$this->assertNotEmpty(exec('find -H ' . APP . Configure::read('Apps.configDir') . DS . 'www.example.com.php'));
		// clean up
		exec('unlink ' . APP . Configure::read('Apps.configDir') . DS . 'www.example.com.php');
	}

	public function testDeleteLink() {
		$this->ServerAlias->save(array('application_id' => 1, 'domain' => 'www.example.com'));
		$this->ServerAlias->add();
		$this->assertNotEmpty(exec('find -H ' . APP . Configure::read('Apps.configDir') . DS . 'www.example.com.php'));
		$this->ServerAlias->delete();
		$this->assertEmpty(exec('find -H ' . APP . Configure::read('Apps.configDir') . DS . 'www.example.com.php'));
	}
}
