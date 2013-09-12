<?php
App::uses('AppShell', 'Console/Command');
class CurrentShell extends AppShell {

	public function main() {
		if (empty($this->args[0])) {
			return $this->err('Supply document_root');
		}
		$documentRoot = $this->args[0];
		// print current application
		if (empty($this->args[1])) {
			$fileName = $documentRoot . DS . Configure::read('Apps.configDir') . DS . 'current_application';
			if ($this->fileExists($fileName)) {
				echo $this->exec('readlink ' . $fileName);
				return;
			} else {
				return $this->err('current_application does not exist');
			}
		}
		// link current application
		else {
			$fileName = $documentRoot . DS . Configure::read('Apps.configDir') . DS . $this->args[1] . '.php';
			if ($this->fileExists($fileName)) {
				$this->exec('ln -fs ' . $fileName . ' ' . $documentRoot . DS . Configure::read('Apps.configDir') . DS . 'current_application');
				return;
			} else {
				return $this->err('Not an application');
			}
		}
	}

	protected function fileExists($fileName) {
		$file = new File($fileName);
		return $file->exists();
	}

	protected function exec($command) {
		return exec($command);
	}
}
