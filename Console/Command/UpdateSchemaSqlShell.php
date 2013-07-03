<?php
class UpdateSchemaSqlShell extends AppShell {

	public $uses = array('Apps.DocumentRoot', 'Apps.Application');

	public function main() {
		if (empty($this->args)) {
			$this->error('Supply DocumentRoot');
		}
		$this->DocumentRoot->recursive = 2;
		$documentRoot = $this->DocumentRoot->find('first', array('conditions' => array('absolute_path' => $this->args[0])));
		if (!$documentRoot) {
			$this->error('No valid DocumentRoot');
		}
		if (empty($documentRoot['Application'])) {
			$this->error('DocumentRoot has no application, yet.');
		}
		$absolutePath = $documentRoot['DocumentRoot']['absolute_path'];
		$database = $documentRoot['Application'][0]['Database']['database'];
		$dbSource = $this->DocumentRoot->getDataSource();
		$user = $dbSource->config['login'];
		$password = $dbSource->config['password'];
		echo $cmd = 'mysqldump -d -u ' . $user . ' -p' . $password . ' ' . $database . ' > ' . $absolutePath . DS . Configure::read('Apps.sqlDir') . DS . Configure::read('Apps.schemaFile');
		exec($cmd);
	}
}
