<?php
Configure::write('Apps.dbConfig', 'default');
Configure::write('Apps.domain', 'example.com');
Configure::write('Apps.httpdRoot', '/etc/apache2');
Configure::write('Apps.restartCmd', '/usr/bin/sudo /usr/sbin/apachectl graceful');
Configure::write('Apps.sqlDir', 'Plugin' . DS . 'Apps' . DS . 'Config' . DS . 'Schema');
Configure::write('Apps.schemaFile', 'schema.php');
//Configure::write('Apps.tablesFile', 'rows.sql');
Configure::write('Apps.configDir', APP_DIR . DS . 'Config' . DS . 'applications');
Configure::write('Apps.dumpDir', TMP);
Configure::write('Apps.cakePath', 'lib' . DS . 'Cake' . DS . 'Console' . DS . 'cake');
