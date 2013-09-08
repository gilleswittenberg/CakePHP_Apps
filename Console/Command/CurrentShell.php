<?php
class CurrentShell extends AppShell {

	public function main() {
		// print current application
		if (empty($this->args[0])) {
			$this->error('Supply document_root');
		}
		$documentRoot = $this->args[0];
		if (empty($this->args[1])) {
			$file = new File($documentRoot . DS . Configure::read('Apps.configDir') . DS . 'current_application');
			if ($file->exists()) {
				//echo exec('readlink ' . APP . Configure::read('Apps.configDir') . DS . 'current_application');
				echo exec('readlink ' . $documentRoot . DS . Configure::read('Apps.configDir') . DS . 'current_application');
			} else {
				$this->err('current_application does not exist');
			}
		}
		// set current application
		else {
			$file = new File($documentRoot . DS . Configure::read('Apps.configDir') . DS . $this->args[1] . '.php');
			if ($file->exists()) {
				exec('ln -fs ' . $documentRoot . DS . Configure::read('Apps.configDir') . DS . $this->args[1] . '.php ' . $documentRoot . DS . Configure::read('Apps.configDir') . DS . 'current_application');
			} else {
				$this->err('Not an application');
			}
		}
	}
}
