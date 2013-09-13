<?php

App::uses('ShellDispatcher', 'Console');
App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('Shell', 'Console');
App::uses('CurrentShell', 'Apps.Console/Command');


/**
 * CurrentShellTest class
 *
 * @package       Plugin.Apps.Test.Case.Console.Command
 */
class IntegrationCurrentShellTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.apps.document_root', 'plugin.apps.application'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$in = $this->getMock('ConsoleInput', array(), array(), '', false);
		$this->Shell = $this->getMock(
			'CurrentShell',
			array('in', 'out', 'hr', 'createFile', 'error', 'err', '_stop'),
			array($out, $out, $in)
		);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		if (!empty($this->file) && $this->file instanceof File) {
			$this->file->delete();
			unset($this->file);
		}
	}

	public function testLinking() {
		// remove existing link
		exec('unlink ' . APP . Configure::read('Apps.configDir') . DS . 'current_application');
		// create test config file
		$file = new File(APP . Configure::read('Apps.configDir') . DS . 'application-9.example.com.php');
		$file->create();
		$file->write('test');
		// set expects
		$this->Shell->expects($this->never())
			->method('error');
		// set current
		$this->Shell->args = array(ROOT, 'application-9.example.com');
		$this->Shell->main();
		// check current
		$fileLink = new File(APP . Configure::read('Apps.configDir') . DS . 'current_application');
		$this->assertEquals($fileLink->read(), 'test');
		// clean up
		$file->delete();
	}
}
