<div class="databases index">
	<h2><?php echo __('Databases'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('application_id'); ?></th>
			<th><?php echo $this->Paginator->sort('host'); ?></th>
			<th><?php echo $this->Paginator->sort('database'); ?></th>
			<th><?php echo $this->Paginator->sort('login'); ?></th>
			<th><?php echo $this->Paginator->sort('password'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($databases as $database): ?>
	<tr>
		<td><?php echo h($database['Database']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($database['Application']['id'], array('controller' => 'applications', 'action' => 'view', $database['Application']['id'])); ?>
		</td>
		<td><?php echo h($database['Database']['host']); ?>&nbsp;</td>
		<td><?php echo h($database['Database']['database']); ?>&nbsp;</td>
		<td><?php echo h($database['Database']['login']); ?>&nbsp;</td>
		<td><?php echo h($database['Database']['password']); ?>&nbsp;</td>
		<td><?php echo h($database['Database']['modified']); ?>&nbsp;</td>
		<td><?php echo h($database['Database']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $database['Database']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $database['Database']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $database['Database']['id']), null, __('Are you sure you want to delete # %s?', $database['Database']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Database'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
	</ul>
</div>
