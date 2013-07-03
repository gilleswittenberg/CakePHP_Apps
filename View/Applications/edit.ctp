<div class="applications form">
<?php echo $this->Form->create('Application'); ?>
	<fieldset>
		<legend><?php echo __('Edit Application'); ?></legend>
	<?php
		echo $this->Form->input('id');
		//echo $this->Form->input('document_root_id');
		echo $this->Form->value('DocumentRoot.absolute_path');
		echo '<br>';
		echo $this->Form->value('server_name');
		//echo $this->Form->input('server_name');
		//echo $this->Form->input('slug');
		echo $this->Form->input('status', array('options' => array('0' => '0', '1' => '1')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Application.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Application.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Applications'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Domains'), array('controller' => 'domains', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Domain'), array('controller' => 'domains', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Databases'), array('controller' => 'databases', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Database'), array('controller' => 'databases', 'action' => 'add')); ?> </li>
	</ul>
</div>
