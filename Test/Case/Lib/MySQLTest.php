<?php
App::uses('MySQLLib', 'Apps.Lib');
App::uses('File', 'Utility');

class MySQLTest extends CakeTestCase {

    public function setUp() {
        parent::setUp();
        $this->MySQL = new MySQLLib();
		$this->db = ConnectionManager::getDataSource(Configure::read('Apps.dbConfig'));
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->MySQL);
    }

    public function testCreateDatabase() {
		// check if not already existing
		$this->assertEmpty($this->db->fetchAll("SHOW DATABASES LIKE 'application-123'", false));
		// create database
		$this->MySQL->createDatabase('application-123');
		$this->assertNotEmpty($this->db->rawQuery("SHOW DATABASES LIKE 'application-123'"));
		// clean up
		$this->db->rawQuery('DROP DATABASE IF EXISTS `application-123`');
    }

	public function testDatabaseExists() {
		$this->assertFalse($this->MySQL->databaseExists('application-123'));
		$this->MySQL->createDatabase('application-123');
		$this->assertTrue($this->MySQL->databaseExists('application-123'));
		// clean up
		$this->db->rawQuery('DROP DATABASE IF EXISTS `application-123`');
		$this->assertEmpty($this->db->fetchAll("SHOW DATABASES LIKE 'application-123'", false));
	}

	public function testDropDatabase() {
		// check if not already existing
		$this->assertEmpty($this->db->fetchAll("SHOW DATABASES LIKE 'application-123'", false));
		// create database
		$this->MySQL->createDatabase('application-123');
		$this->assertNotEmpty($this->db->rawQuery("SHOW DATABASES LIKE 'application-123'"));
		// drop database
		$this->MySQL->dropDatabase('application-123');
		$this->assertEmpty($this->db->fetchAll("SHOW DATABASES LIKE 'application-123'", false));
    }

	public function testCreateUser() {
		// check if not already existing
		$this->assertEmpty($this->db->fetchAll("SELECT User FROM mysql.user WHERE User='user_name'", false));
		// create user
		$this->MySQL->createUser('user_name', 'password');
		$this->assertNotEmpty($this->db->fetchAll("SELECT USER FROM mysql.user WHERE User='user_name'", false));
		// clean up
		$this->db->rawQuery("DROP USER 'user_name'@'localhost'");
		$this->assertEmpty($this->db->fetchAll("SELECT User FROM mysql.user WHERE User='user_name'", false));
	}

	public function testDropUser() {
		// check if not already existing
		$this->assertEmpty($this->db->fetchAll("SELECT USER FROM mysql.user WHERE User='user_name_drop'", false));
		// create user
		$this->MySQL->createUser('user_name_drop', 'password');
		$this->assertNotEmpty($this->db->fetchAll("SELECT USER FROM mysql.user WHERE User='user_name_drop'", false));
		// drop user
		$this->MySQL->dropUser('user_name_drop');
		$this->assertEmpty($this->db->fetchAll("SELECT USER FROM mysql.user WHERE User='user_name_drop'", false));
	}

	public function testDump() {
		$database = $this->db->config['database'];
		$name = $database . '_' . date('m-d-Y-His') . '.sql';
		$file = new File(TMP . $name);
		$this->assertFalse($file->exists());
		$this->MySQL->dump($database, $name);
		$this->assertTrue($file->exists());
		// clean up
		$file->delete();
	}
}
