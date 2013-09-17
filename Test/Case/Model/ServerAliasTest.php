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
}
