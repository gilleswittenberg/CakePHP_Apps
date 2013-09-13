<?php
App::uses('Database', 'Apps.Model');
App::uses('File', 'Utility');

/**
 * Database Test Case
 *
 */
class DatabaseTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.apps.database',
		'plugin.apps.application',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Database = ClassRegistry::init('Apps.Database');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Database);

		parent::tearDown();
	}

	public function testAfterSave() {
		$this->Database->saveAssociated(array('Application' => array('document_root_id' => 1), 'Database' => array('id' => '')));
		$database = $this->Database->find('first', array('conditions' => array('Database.id' => $this->Database->id)));
		$this->assertEquals('application-' . $database['Application']['id'], $database['Database']['database']);
		$this->assertEquals('application-' . $database['Application']['id'], $database['Database']['login']);
	}
}
