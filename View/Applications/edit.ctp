<div class="applications view">
	<dl>
		<dt><?php echo __('Document Root'); ?></dt>
		<dd>
			<?php echo $this->Form->value('DocumentRoot.absolute_path'); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Server Name'); ?></dt>
		<dd>
			<?php echo $this->Form->value('Application.server_name'); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="applications form">
<?php echo $this->Form->create('Application'); ?>
	<fieldset>
		<legend><?php echo __('Change status'); ?></legend>
		<?php echo $this->Form->input('status', array('options' => array('0' => '0', '1' => '1'))); ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Application.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Application.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Applications'), array('action' => 'index')); ?></li>
	</ul>
</div>
