<?php

class ApacheLib {

	public function writeDirective($serverName, $documentRoot, $serverAliases = array()) {
		$content = $this->getDirectiveContent($serverName, $documentRoot, $serverAliases);
		// write file
		$filename = Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . $serverName;
		$file = new File($filename, true, 0664);
		$file->write($content);
		return array('content' => $content, 'filename' => $filename);
	}

	public function enableDirective($serverName) {
		exec('/usr/bin/sudo /usr/sbin/a2ensite ' . $serverName);
	}

	public function disableDirective($serverName) {
		exec('/usr/bin/sudo /usr/sbin/a2dissite ' . $serverName);
	}

	public function deleteDirective($serverName) {
		$filename = Configure::read('Apps.httpdRoot') . DS . 'sites-available' . DS . $serverName;
		$file = new File($filename);
		$file->delete();
	}

	public function restart() {
		exec(Configure::read('Apps.restartCmd'));
	}

	protected function getDirectiveContent($serverName, $documentRoot, $serverAliases) {
		// open virtual host
		$content = '<VirtualHost *:80>' . "\n";
		// ServerName
		$content .= 'ServerName ' . $serverName . "\n";
		// serverAliases
		foreach ($serverAliases as $serverAlias) {
			$content .= 'ServerAlias ' . $serverAlias['domain'] . "\n";
		}
		// document_root
		$content .= 'DocumentRoot ' . $documentRoot . "\n";
		// redirect favicon.ico
		$content .= 'RewriteEngine on' . "\n";
		$content .= 'RewriteRule ^/favicon\.ico$ /applications/' . $serverName . '/favicon.ico [L]' . "\n";
		// close virtual host
		$content .= '</VirtualHost>';
		return $content;
	}
}
