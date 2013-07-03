<div class="serverAliases view">
<h2><?php  echo __('Server Alias'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($serverAlias['ServerAlias']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Application'); ?></dt>
		<dd>
			<?php echo $this->Html->link($serverAlias['Application']['id'], array('controller' => 'applications', 'action' => 'view', $serverAlias['Application']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Domain'); ?></dt>
		<dd>
			<?php echo h($serverAlias['ServerAlias']['domain']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($serverAlias['ServerAlias']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($serverAlias['ServerAlias']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Server Alias'), array('action' => 'edit', $serverAlias['ServerAlias']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Server Alias'), array('action' => 'delete', $serverAlias['ServerAlias']['id']), null, __('Are you sure you want to delete # %s?', $serverAlias['ServerAlias']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Server Aliases'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Server Alias'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
	</ul>
</div>
