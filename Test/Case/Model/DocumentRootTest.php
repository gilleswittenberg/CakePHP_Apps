<?php
App::uses('DocumentRoot', 'Apps.Model');

/**
 * DocumentRoot Test Case
 *
 */
class DocumentRootTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.apps.document_root',
		'plugin.apps.application',
		'plugin.apps.database',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->DocumentRoot = ClassRegistry::init('Apps.DocumentRoot');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->DocumentRoot);

		parent::tearDown();
	}

	public function testDelete() {
		$this->DocumentRoot->id = 1;
		$this->assertFalse($this->DocumentRoot->delete());
	}
}
