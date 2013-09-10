<?php
if (!Configure::read('debug')):
	throw new NotFoundException();
endif;
App::uses('Debugger', 'Utility');
?>
<h2><?php echo __('Check Config for CakePHP Apps Plugin'); ?></h2>

<p>
	<?php
		echo '<span class="notice success">';
			echo __d('cake_dev', 'Apache is being run by user %s.', exec('whoami'));
		echo '</span>';
	?>
</p>

<p>
	<?php
		if (ConnectionManager::getDataSource(Configure::read('Apps.dbConfig'))):
			echo '<span class="notice success">';
				echo __d('cake_dev', 'Database Config %s is present.', Configure::read('Apps.dbConfig'));
			echo '</span>';
		else:
			echo '<span class="notice">';
				echo __d('cake_dev', 'Database Config %s is not present.', Configure::read('Apps.dbConfig'));
			echo '</span>';
		endif;
	?>
</p>

<p>
	<?php
		if (is_writable(Configure::read('Apps.httpdRoot') . DS . 'sites-available')):
			echo '<span class="notice success">';
				echo __d('cake_dev', '%s directory is writable.', Configure::read('Apps.httpdRoot') . DS . 'sites-available');
			echo '</span>';
		else:
			echo '<span class="notice">';
				echo __d('cake_dev', '%s directory is NOT writable.', Configure::read('Apps.httpdRoot') . DS . 'sites-available');
			echo '</span>';
		endif;
	?>
</p>

<p>
	<?php
		if (file_exists(Configure::read('Apps.sqlDir') . DS . Configure::read('Apps.schemaFile'))):
			echo '<span class="notice success">';
				echo __d('cake_dev', 'Schema file is present.');
			echo '</span>';
		else:
			echo '<span class="notice">';
				echo __d('cake_dev', 'Schema file is not present.');
			echo '</span>';
		endif;
	?>
</p>

<?php
foreach ($documentRoots as $documentRoot) {
	$dir = $documentRoot['DocumentRoot']['absolute_path'] . DS . Configure::read('Apps.configDir');
	if (is_dir($dir)):
		if (is_writable($dir)):
			echo '<span class="notice success">';
				echo $dir . __d('cake_dev', ' is writable.');
			echo '</span>';
		else:
			echo '<span class="notice">';
				echo $dir . __d('cake_dev', ' is not writable.');
			echo '</span>';
		endif;
	else:
		echo '<span class="notice">';
			echo $dir . __d('cake_dev', ' is not a dir.');
		echo '</span>';
	endif;
}
?>
<p>
	<?php
		if (is_dir(Configure::read('Apps.dumpDir'))):
			echo '<span class="notice success">';
				echo __d('cake_dev', 'DumpDir is a dir.');
			echo '</span>';
				if (is_writable(Configure::read('Apps.dumpDir'))):
					echo '<span class="notice success">';
						echo __d('cake_dev', 'DumpDir is writable.');
					echo '</span>';
				else:
					echo '<span class="notice">';
						echo __d('cake_dev', 'DumpDir is not writable.');
					echo '</span>';
				endif;
		else:
			echo '<span class="notice">';
				echo __d('cake_dev', 'DumpDir is not a dir.');
			echo '</span>';
		endif;
	?>
</p>

<!--
check if database user is granted for needed actions
-->
