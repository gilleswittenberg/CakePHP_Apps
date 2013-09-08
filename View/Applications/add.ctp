<div class="applications form">
<?php echo $this->Form->create('Application'); ?>
	<fieldset>
		<legend><?php echo __('Add Application'); ?></legend>
	<?php
		echo $this->Form->input('document_root_id');
		echo $this->Form->input('server_name');
		echo $this->Form->input('Database.database', array('required' => false));
		echo $this->Form->input('Database.login', array('required' => false));
		//echo $this->Form->input('Database.password', array('required' => false));
		//echo $this->Form->input('slug');
		//echo $this->Form->input('status', array('type' => 'hidden'));
		//echo $this->Form->input('Domain.0.domain');
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
