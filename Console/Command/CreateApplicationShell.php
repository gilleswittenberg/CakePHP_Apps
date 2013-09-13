<?php
App::uses('AppShell', 'Console/Command');
class CreateApplicationShell extends AppShell {

	public $uses = array('DocumentRoot', 'Application');

	public function main() {
		if (empty($this->args[0])) {
			$this->error('Supply DocumentRoot');
			return false;
		}
		$documentRoot = $this->DocumentRoot->find('first', array('conditions' => array('absolute_path' => $this->args[0])));
		if (empty($documentRoot)) {
			$this->error('No valid DocumentRoot');
			return false;
		}
		$this->Application->saveAssociated(array('Application' => array('document_root_id' => $documentRoot['DocumentRoot']['id']), 'Database' => array('id' => '')));
	}
}
