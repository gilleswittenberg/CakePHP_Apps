<div class="applications form">
<?php echo $this->Form->create('Application'); ?>
	<fieldset>
		<legend><?php echo __('Add Application'); ?></legend>
	<?php
		echo $this->Form->input('document_root_id');
		echo $this->Form->input('server_name');
		echo $this->Form->input('Database.database', array('required' => false));
		echo $this->Form->input('Database.login', array('required' => false));
		//echo $this->Form->input('status', array('type' => 'hidden'));
		//echo $this->Form->input('Database.password', array('required' => false));
		//echo $this->Form->input('slug');
		for ($i = 0; $i < 3; $i++) {
			echo $this->Form->input('ServerAlias.' . $i . '.domain', array('required' => false, 'label' => __('ServerAlias %d ', $i + 1)));
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Applications'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List DocumentRoots'), array('controller' => 'document_roots', 'action' => 'index')); ?></li>
	</ul>
</div>
