<?php
App::uses('AppShell', 'Console/Command');
class CurrentShell extends AppShell {

	public $uses = array('Apps.DocumentRoot');

	public function main() {
		if (empty($this->args[0])) {
			return $this->error('Supply DocumentRoot');
		}
		$documentRoot = $this->getDocumentRoot($this->args[0]);
		if (empty($documentRoot)) {
			$this->error('DocumentRoot does not exist');
			return false;
		}
		// print current application
		if (empty($this->args[1])) {
			$fileName = $documentRoot['DocumentRoot']['app_path'] . Configure::read('Apps.configDir') . DS . 'current_application';
			if ($this->fileExists($fileName)) {
				echo $this->exec('readlink ' . $fileName);
				return;
			} else {
				return $this->error('current_application does not exist');
			}
		}
		// link current application
		else {
			$configDir = $documentRoot['DocumentRoot']['app_path'] . Configure::read('Apps.configDir') . DS;
			$fileName = $configDir . $this->args[1] . '.php';
			if ($this->fileExists($fileName)) {
				$this->exec('ln -fs ' . $fileName . ' ' . $configDir . 'current_application');
				return;
			} else {
				return $this->error('Not an application');
			}
		}
	}

	protected function getDocumentRoot($absolutePath) {
		return $this->DocumentRoot->findByAbsolutePath($absolutePath);
	}

	protected function fileExists($fileName) {
		$file = new File($fileName);
		return $file->exists();
	}

	protected function exec($command) {
		return exec($command);
	}
}
