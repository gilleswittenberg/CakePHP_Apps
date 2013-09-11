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
		$output = (int)exec('sudo -n uptime 2>&1|grep "load"|wc -l');
		if ($output) {
			echo '<span class="notice success">';
				echo __d('cake_dev', 'Apache user is sudoer.');
			echo '</span>';
		} else {
			echo '<span class="notice">';
				echo __d('cake_dev', 'Apache user is no sudoer.');
			echo '</span>';
		}
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
<?php //@ToDo check if database user is privileged to create, delete and grant ?>

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

<h2><?php echo __('Document Roots'); ?></h2>
<?php
foreach ($documentRoots as $documentRoot): ?>
	<h3><?php echo $documentRoot['DocumentRoot']['absolute_path']; ?></h3>
	<?php
	// configDir
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

	// schema.php
	$schemaFile = $documentRoot['DocumentRoot']['absolute_path'] . DS . $documentRoot['DocumentRoot']['app_dir'] . DS . 'Config' . DS . 'Schema' . DS . Configure::read('Apps.schemaFile');
	if (is_file($schemaFile)):
		echo '<span class="notice success">';
			echo __d('cake_dev', '%s is present.', $schemaFile);
		echo '</span>';
	else:
		echo '<span class="notice">';
			echo __d('cake_dev', '%s is not present.', $schemaFile);
		echo '</span>';
	endif;
endforeach;
?>

<!--
-->
