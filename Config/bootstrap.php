<?php
Configure::write('Apps.dbConfig', 'default');
Configure::write('Apps.domain', 'example.com');
Configure::write('Apps.httpdRoot', '/etc/apache2');
Configure::write('Apps.restartCmd', '/usr/bin/sudo /usr/sbin/apachectl graceful');
Configure::write('Apps.configDir', 'Config' . DS . 'applications');
Configure::write('Apps.dumpDir', TMP);
Configure::write('Apps.cakePath', APP . 'Console' . DS . 'cake');
