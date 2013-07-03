<div class="serverAliases form">
<?php echo $this->Form->create('ServerAlias'); ?>
	<fieldset>
		<legend><?php echo __('Add Server Alias'); ?></legend>
	<?php
		echo $this->Form->input('application_id');
		echo $this->Form->input('domain');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Server Aliases'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
	</ul>
</div>
