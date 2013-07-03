<div class="databases form">
<?php echo $this->Form->create('Database'); ?>
	<fieldset>
		<legend><?php echo __('Add Database'); ?></legend>
	<?php
		echo $this->Form->input('application_id');
		echo $this->Form->input('host');
		echo $this->Form->input('database');
		echo $this->Form->input('login');
		echo $this->Form->input('password');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Databases'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
	</ul>
</div>
