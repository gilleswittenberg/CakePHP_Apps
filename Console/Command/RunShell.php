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
		$snapshot = $this->getLatestSchemaSnapshot($target);
		if (!$snapshot) {
			$this->error('No snapshot');
		}
		$command = 'schema update --snapshot ' . $snapshot . ' --yes';
 		// run schema shell
		foreach ($applications as $application) {
			$cakePath = Configure::read('Apps.cakePath');
			$this->setCurrent($application['Application']['server_name'], $application['DocumentRoot']['absolute_path']);
			exec($application['DocumentRoot']['absolute_path'] . DS . $cakePath . ' ' . $command);
		}
	}

	public function updateSchemaCurrent() {
		$absolutePath = $this->args[0];
		$snapshot = $this->getLatestSchemaSnapshot($absolutePath);
		if (!$snapshot) {
			$this->error('No snapshot');
		}
		$command = 'schema --app ' . $absolutePath . ' update --snapshot ' . $snapshot . ' --yes';
		$cakePath = Configure::read('Apps.cakePath');
		exec($absolutePath . DS . $cakePath . ' ' . $command);
	}

	protected function getLatestSchemaSnapshot($absolutePath) {
		$folder = new Folder($absolutePath . DS . 'Config' . DS . 'Schema');
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

	protected function setCurrent($application, $appDir) {
		$cakePath = Configure::read('Apps.cakePath') ?: 'Console' . DS . 'cake';
		exec($appDir . DS . $cakePath . ' Apps.current ' . $application);
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

	protected function getCommand() {
		if (count($this->args) < 2) {
			$this->error('No command');
		} else {
			return $this->args[1];
		}
	}
}
