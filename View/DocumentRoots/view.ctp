<div class="documentRoots view">
<h2><?php  echo __('Document Root'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($documentRoot['DocumentRoot']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Absolute Path'); ?></dt>
		<dd>
			<?php echo h($documentRoot['DocumentRoot']['absolute_path']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($documentRoot['DocumentRoot']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($documentRoot['DocumentRoot']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<!--<li><?php echo $this->Html->link(__('Edit Document Root'), array('action' => 'edit', $documentRoot['DocumentRoot']['id'])); ?> </li>-->
		<li><?php echo $this->Form->postLink(__('Delete Document Root'), array('action' => 'delete', $documentRoot['DocumentRoot']['id']), null, __('Are you sure you want to delete # %s?', $documentRoot['DocumentRoot']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Document Roots'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Document Root'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Applications'); ?></h3>
	<?php if (!empty($documentRoot['Application'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Document Root Id'); ?></th>
		<th><?php echo __('Server Name'); ?></th>
		<th><?php echo __('Slug'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($documentRoot['Application'] as $application): ?>
		<tr>
			<td><?php echo $application['id']; ?></td>
			<td><?php echo $application['document_root_id']; ?></td>
			<td><?php echo $application['server_name']; ?></td>
			<td><?php echo $application['slug']; ?></td>
			<td><?php echo $application['status']; ?></td>
			<td><?php echo $application['modified']; ?></td>
			<td><?php echo $application['created']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'applications', 'action' => 'view', $application['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'applications', 'action' => 'edit', $application['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'applications', 'action' => 'delete', $application['id']), null, __('Are you sure you want to delete # %s?', $application['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
