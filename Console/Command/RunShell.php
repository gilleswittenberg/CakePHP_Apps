<?php
App::uses('Folder', 'Utility');
class RunShell extends AppShell {

	public $uses = array('Apps.Application', 'Apps.DocumentRoot', 'Apps.Database');

    public function main() {
		$target = $this->getTarget();
		$command = $this->getCommand();
		$applications = $this->getApplications($target);
		foreach ($applications as $application) {
			$this->setCurrent($application['Application']['server_name'], $application['DocumentRoot']['absolute_path']);
			$this->run($application['DocumentRoot']['absolute_path'], $command);
		}
    }

	public function dump() {
		$target = $this->getTarget();
		$applications = $this->getApplications($target);
		foreach ($applications as $application) {
			$this->setCurrent($application['Application']['server_name'], $application['DocumentRoot']['absolute_path']);
			$this->Database->dump($application['Application']['slug']);
			$this->out('Dumping ' . $application['Application']['slug']);
		}
	}

	public function updateSchema() {
		$target = $this->getTarget();
		if ($target === 'all') {
			$this->error('Cannot run updateSchema for all');
		}
		$applications = $this->getApplications($target);
		if (empty($applications)) {
			$this->error('No applications');
		}
		$appDir = $this->getAppDir($target);
		$snapshot = $this->getLatestSchemaSnapshot($target . DS . $appDir);
		if (!$snapshot) {
			$this->error('No snapshot');
		}
		$command = 'schema update --snapshot ' . $snapshot . ' --yes';
 		// run schema shell
		foreach ($applications as $application) {
			$absolutePath = $application['DocumentRoot']['absolute_path'];
			$this->setCurrent($application['Application']['server_name'], $absolutePath);
			$appPath = empty($appDir) ? $absolutePath : $absolutePath . DS . $appDir;
			exec(APP . Configure::read('Apps.cakePath') . ' -app ' . $appPath . ' ' . $command);
		}
	}

	public function updateSchemaCurrent() {
		$absolutePath = $this->args[0];
		$appPath = !empty($this->args[1]) ? $absolutePath . DS . $this->args[1] : $absolutePath;
		$snapshot = $this->getLatestSchemaSnapshot($appPath);
		if (!$snapshot) {
			$this->error('No snapshot');
		}
		$command = 'schema -app ' . $appPath . ' update --snapshot ' . $snapshot . ' --yes';
		$cakePath = Configure::read('Apps.cakePath');
		exec(APP . $cakePath . ' ' . $command);
	}

	protected function getLatestSchemaSnapshot($appPath) {
		$folder = new Folder($appPath . DS . 'Config' . DS . 'Schema');
		$result = $folder->read();
		$files = $result[1];
		$snapshot = null;
		$count = 1;
		while (in_array('schema_' . $count . '.php', $files)) {
			$snapshot = $count;
			$count++;
		}
		return $snapshot;
	}

	protected function setCurrent($application, $absolutePath) {
		$cakePath = Configure::read('Apps.cakePath') ?: 'Console' . DS . 'cake';
		exec(APP . $cakePath . ' Apps.current ' . $absolutePath . ' ' . $application);
	}

	protected function run($appDir, $command) {
		$cakePath = Configure::read('Apps.cakePath') ?: 'Console' . DS . 'cake';
		exec($appDir . DS . $cakePath . ' ' . $command);
	}

	protected function getTarget() {
		if (empty($this->args)) {
			$this->error('No target');
		}
		$arg0 = $this->args[0];
		if ($arg0 === 'all') {
			return 'all';
		} else if ($this->DocumentRoot->hasAny(array('absolute_path' => $arg0))) {
			return $arg0;
		}
		$this->error('Invalid target: ' . $arg0);
	}

	protected function getApplications($target) {
		$conditions = null;
		if ($target !== 'all') {
			$conditions = array('DocumentRoot.absolute_path' => $target);
		}
		return $this->Application->find('all', array('conditions' => $conditions));
	}

	protected function getAppDir($target) {
		$application = $this->Application->find('first', array('conditions' => array('DocumentRoot.absolute_path' => $target)));
		return $application['DocumentRoot']['app_dir'];
	}

	protected function getCommand() {
		if (count($this->args) < 2) {
			$this->error('No command');
		} else {
			return $this->args[1];
		}
	}
}
