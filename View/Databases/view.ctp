<div class="databases view">
<h2><?php  echo __('Database'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($database['Database']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Application'); ?></dt>
		<dd>
			<?php echo $this->Html->link($database['Application']['id'], array('controller' => 'applications', 'action' => 'view', $database['Application']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Host'); ?></dt>
		<dd>
			<?php echo h($database['Database']['host']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Database'); ?></dt>
		<dd>
			<?php echo h($database['Database']['database']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Login'); ?></dt>
		<dd>
			<?php echo h($database['Database']['login']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Password'); ?></dt>
		<dd>
			<?php echo h($database['Database']['password']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($database['Database']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($database['Database']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Database'), array('action' => 'edit', $database['Database']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Database'), array('action' => 'delete', $database['Database']['id']), null, __('Are you sure you want to delete # %s?', $database['Database']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Databases'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Database'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
	</ul>
</div>
