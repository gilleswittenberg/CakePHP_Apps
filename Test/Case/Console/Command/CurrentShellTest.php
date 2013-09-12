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
class CurrentShellTest extends CakeTestCase {

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
			array('in', 'out', 'hr', 'createFile', 'error', 'err', '_stop', 'fileExists', 'exec'),
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

	public function testEmptyArgs() {
		$this->Shell->expects($this->once())
			->method('error');
		$this->Shell->main();
	}

	public function testNonExistingDocumentRoot() {
		$this->Shell->expects($this->once())
			->method('error');
		$this->Shell->args = array('test');
		$this->Shell->main();
	}

	public function testExistingDocumentRoot() {
		$this->Shell->expects($this->never())
			->method('error');
		$this->Shell->expects($this->once())
			->method('fileExists')
			->will($this->returnValue(true));
		$this->Shell->args = array(APP);
		$this->Shell->main();
	}

	public function testLinking() {
		$this->Shell->expects($this->never())
			->method('error');
		$this->Shell->expects($this->once())
			->method('fileExists')
			->will($this->returnValue(true));
		$this->Shell->expects($this->once())
			->method('exec');
		$this->Shell->args = array(APP, 'application-9.example.com');
		$this->Shell->main();
	}
}
