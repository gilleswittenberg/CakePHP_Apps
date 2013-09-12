<?php

App::uses('ShellDispatcher', 'Console');
App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('Shell', 'Console');
App::uses('RunShell', 'Apps.Console/Command');


/**
 * CurrentShellTest class
 *
 * @package       Plugin.Apps.Test.Case.Console.Command
 */
class RunShellTest extends CakeTestCase {

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

		$this->out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$this->in = $this->getMock('ConsoleInput', array(), array(), '', false);
		$this->Shell = $this->getMock(
			'RunShell',
			array('in', 'out', 'hr', 'createFile', 'error', 'err', '_stop'),
			array($this->out, $this->out, $this->in)
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

	public function testMainEmptyArgs0() {
		$this->Shell->expects($this->once())
			->method('error');
		$this->Shell->main();
	}

	public function testMainEmptyArgs1() {
		$this->Shell->expects($this->once())
			->method('error');
		$this->Shell->args = array(APP);
		$this->Shell->main();
	}

	public function testMain() {
		$this->Shell = $this->getMock(
			'RunShell',
			array('in', 'out', 'hr', 'createFile', 'error', 'err', '_stop', 'setCurrent', 'run'),
			array($this->out, $this->out, $this->in)
		);
		$this->Shell->expects($this->atLeastOnce())
			->method('setCurrent');
		$this->Shell->expects($this->atLeastOnce())
			->method('run');
		$this->Shell->args = array(APP, 'ls');
		$this->Shell->main();
	}

	public function testDump() {
		$this->Shell = $this->getMock(
			'RunShell',
			array('in', 'out', 'hr', 'createFile', 'error', 'err', '_stop', 'setCurrent'),
			array($this->out, $this->out, $this->in)
		);
		$this->Shell->expects($this->atLeastOnce())
			->method('setCurrent');
		$this->Shell->Database = $this->getMock('Database');
		$this->Shell->Database->expects($this->atLeastOnce())
			->method('dump');
		$this->Shell->args = array(APP, 'ls');
		$this->Shell->dump();
	}

	public function testUpdataSchema() {
		$this->Shell = $this->getMock(
			'RunShell',
			array('in', 'out', 'hr', 'createFile', 'error', 'err', '_stop', 'setCurrent', 'run', 'getLatestSchemaSnapshot', 'exec'),
			array($this->out, $this->out, $this->in)
		);
		$this->Shell->expects($this->once())
			->method('getLatestSchemaSnapshot')
			->will($this->returnValue(1));
		$this->Shell->expects($this->atLeastOnce())
			->method('setCurrent');
		$this->Shell->expects($this->atLeastOnce())
			->method('exec');
		$this->Shell->args = array(APP);
		$this->Shell->updateSchema();
	}

	public function testUpdataSchemaCurrent() {
		$this->Shell = $this->getMock(
			'RunShell',
			array('in', 'out', 'hr', 'createFile', 'error', 'err', '_stop', 'setCurrent', 'getLatestSchemaSnapshot', 'exec'),
			array($this->out, $this->out, $this->in)
		);
		$this->Shell->expects($this->once())
			->method('getLatestSchemaSnapshot')
			->will($this->returnValue(1));
		$this->Shell->expects($this->atLeastOnce())
			->method('exec');
		$this->Shell->args = array(APP);
		$this->Shell->updateSchemaCurrent();
	}
}
