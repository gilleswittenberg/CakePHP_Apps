<?php
class CurrentShell extends AppShell {

	public function main() {
		// print current application
		if (empty($this->args[0])) {
			echo exec('readlink ' . APP . Configure::read('Apps.configDir') . DS . 'current_application');
		}
		// set current application
		else {
			$file = new File(APP . Configure::read('Apps.configDir') . DS . $this->args[0] . '.php');
			if ($file->exists()) {
				exec('ln -fs ' . $this->args[0] . '.php ' . APP . Configure::read('Apps.configDir') . DS . 'current_application');
			} else {
				$this->err('Not an application');
			}
		}
	}
}
