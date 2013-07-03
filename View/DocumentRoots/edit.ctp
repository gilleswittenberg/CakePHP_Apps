<div class="documentRoots form">
<?php echo $this->Form->create('DocumentRoot'); ?>
	<fieldset>
		<legend><?php echo __('Edit Document Root'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('absolute_path');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('DocumentRoot.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('DocumentRoot.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Document Roots'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
	</ul>
</div>
