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

	public function testValidateAbsolutePath() {
		$data = array('absolute_path' => 'relative/path');
		$this->DocumentRoot->set($data);
		$this->assertFalse($this->DocumentRoot->validates());
		$data = array('absolute_path' => '/absolute/path');
		$this->DocumentRoot->set($data);
		$this->assertTrue($this->DocumentRoot->validates());
	}

	public function testValidateAppDir() {
		$data = array('app_dir' => 'dir with spaces');
		$this->DocumentRoot->set($data);
		$this->assertFalse($this->DocumentRoot->validates());
	}

	public function testBeforeSaveAbsolutePath() {
		$data = array('absolute_path' => '/absolute/path/');
		$this->DocumentRoot->create();
		$this->DocumentRoot->save($data);
		$this->assertEquals($this->DocumentRoot->field('absolute_path'), '/absolute/path');
	}

	public function testBeforeValidateAppDir() {
		$data = array('app_dir' => '/app/');
		$this->DocumentRoot->create();
		$this->DocumentRoot->save($data);
		$this->assertEquals($this->DocumentRoot->field('app_dir'), 'app');
	}

	public function testDelete() {
		$this->DocumentRoot->id = 1;
		// false cause DocumentRoot.1 has applications
		$this->assertFalse($this->DocumentRoot->delete());
	}
}
