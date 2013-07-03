<div class="serverAliases index">
	<h2><?php echo __('Server Aliases'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('application_id'); ?></th>
			<th><?php echo $this->Paginator->sort('domain'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($serverAliases as $serverAlias): ?>
	<tr>
		<td><?php echo h($serverAlias['ServerAlias']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($serverAlias['Application']['id'], array('controller' => 'applications', 'action' => 'view', $serverAlias['Application']['id'])); ?>
		</td>
		<td><?php echo h($serverAlias['ServerAlias']['domain']); ?>&nbsp;</td>
		<td><?php echo h($serverAlias['ServerAlias']['modified']); ?>&nbsp;</td>
		<td><?php echo h($serverAlias['ServerAlias']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $serverAlias['ServerAlias']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $serverAlias['ServerAlias']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $serverAlias['ServerAlias']['id']), null, __('Are you sure you want to delete # %s?', $serverAlias['ServerAlias']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Server Alias'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
	</ul>
</div>
