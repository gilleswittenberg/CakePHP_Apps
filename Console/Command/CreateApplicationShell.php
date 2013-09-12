<?php
class CreateApplicationShell extends AppShell {

	public $uses = array('DocumentRoot', 'Application');

	public function main() {
		$documentRoot = $this->DocumentRoot->find('first', array('conditions' => array('absolute_path' => $this->args[0])));
		if (!$documentRoot) {
			$this->error('No valid DocumentRoot');
		}
		$this->Application->saveAssociated(array('Application' => array('document_root_id' => $documentRoot['DocumentRoot']['id']), 'Database' => array('id' => '')));
	}
}
