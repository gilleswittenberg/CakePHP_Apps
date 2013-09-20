<?php

App::uses('ShellDispatcher', 'Console');
App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('Shell', 'Console');
App::uses('CreateApplicationShell', 'Apps.Console/Command');
App::uses('Application', 'Apps.Model');

/**
 * CurrentShellTest class
 *
 * @package       Plugin.Apps.Test.Case.Console.Command
 */
class CreateApplicationShellTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.apps.document_root', 'plugin.apps.application', 'plugin.apps.database', 'plugin.apps.server_alias'
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
			'CreateApplicationShell',
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

	public function testEmptyArgs() {
		$this->Shell->expects($this->once())
			->method('error');
		$this->Shell->main();
	}

	public function testNonExistingDocumentRoot() {
		$this->Shell->expects($this->once())
			->method('error');
		$this->Shell->args = array('/non_existing/path');
		$this->Shell->main();
	}

	public function testExistingDocumentRoot() {
		$this->Shell->expects($this->never())
			->method('error');
		$this->Shell->args = array(ROOT);
		$this->Shell->Application = $this->getMockForModel('Application', array('saveAssociated', 'init'));
		$this->Shell->main();
	}
}
