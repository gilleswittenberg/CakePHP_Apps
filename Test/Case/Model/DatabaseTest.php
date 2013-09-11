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

    public function testCreateDatabase() {
		$this->Database->createDatabase('application-123');
		$this->assertNotEmpty($this->Database->query("SHOW DATABASES LIKE 'application-123'"));
		// clean up
		$this->Database->query('DROP DATABASE IF EXISTS `application-123`');
    }

    public function testDropDatabase() {
		$this->Database->createDatabase('application-123');
		$this->assertNotEmpty($this->Database->query("SHOW DATABASES LIKE 'application-123'"));
		$this->Database->dropDatabase('application-123');
		$this->assertEmpty($this->Database->query("SHOW DATABASES LIKE 'application-123'"));
    }

	public function testCreateUser() {
		$this->assertEmpty($this->Database->query("SELECT User FROM mysql.user WHERE User='user_name'", false));
		$this->Database->createUser('user_name');
		$this->assertNotEmpty($this->Database->query("SELECT USER FROM mysql.user WHERE User='user_name'", false));
		// clean up
		$this->Database->query("DROP USER 'user_name'@'localhost'", false);
	}

	public function testDropUser() {
		$this->Database->createUser('user_name');
		$this->assertNotEmpty($this->Database->query("SELECT USER FROM mysql.user WHERE User='user_name'", false));
		$this->Database->dropUser('user_name');
		$this->assertEmpty($this->Database->query("SELECT USER FROM mysql.user WHERE User='user_name'", false));
	}

	public function testDump() {
		$dataSource = ConnectionManager::getDataSource(Configure::read('Apps.dbConfig'));
		$database = $dataSource->config['database'];
		$name = $database . '_' . date('m-d-Y-His') . '.sql';
		$file = new File(TMP . $name);
		$this->assertFalse($file->exists());
		$this->Database->dump($database, $name);
		$this->assertTrue($file->exists());
		// clean up
		$file->delete();
	}

	public function testDatabaseExists() {
		$this->assertFalse($this->Database->databaseExists('application-123'));
		$this->Database->createDatabase('application-123');
		$this->assertTrue($this->Database->databaseExists('application-123'));
		// clean up
		$this->Database->query('DROP DATABASE IF EXISTS `application-123`');
	}

}
