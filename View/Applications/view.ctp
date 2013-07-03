<div class="applications view">
<h2><?php  echo __('Application'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($application['Application']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Document Root Id'); ?></dt>
		<dd>
			<?php echo h($application['Application']['document_root_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Server Name'); ?></dt>
		<dd>
			<?php echo h($application['Application']['server_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Slug'); ?></dt>
		<dd>
			<?php echo h($application['Application']['slug']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($application['Application']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($application['Application']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($application['Application']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Application'), array('action' => 'edit', $application['Application']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Application'), array('action' => 'delete', $application['Application']['id']), null, __('Are you sure you want to delete # %s?', $application['Application']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Applications'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Domains'), array('controller' => 'domains', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Domain'), array('controller' => 'domains', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Databases'), array('controller' => 'databases', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Database'), array('controller' => 'databases', 'action' => 'add')); ?> </li>
	</ul>
</div>
	<div class="related">
		<h3><?php echo __('Related Domains'); ?></h3>
	<?php if (!empty($application['Domain'])): ?>
		<dl>
			<dt><?php echo __('Id'); ?></dt>
		<dd>
	<?php echo $application['Domain']['id']; ?>
&nbsp;</dd>
		<dt><?php echo __('Application Id'); ?></dt>
		<dd>
	<?php echo $application['Domain']['application_id']; ?>
&nbsp;</dd>
		<dt><?php echo __('Domain'); ?></dt>
		<dd>
	<?php echo $application['Domain']['domain']; ?>
&nbsp;</dd>
		<dt><?php echo __('Primary'); ?></dt>
		<dd>
	<?php echo $application['Domain']['primary']; ?>
&nbsp;</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
	<?php echo $application['Domain']['modified']; ?>
&nbsp;</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
	<?php echo $application['Domain']['created']; ?>
&nbsp;</dd>
		</dl>
	<?php endif; ?>
		<div class="actions">
			<ul>
				<li><?php echo $this->Html->link(__('Edit Domain'), array('controller' => 'domains', 'action' => 'edit', $application['Domain']['id'])); ?></li>
			</ul>
		</div>
	</div>
	<div class="related">
	<h3><?php echo __('Related Databases'); ?></h3>
	<?php if (!empty($application['Database'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Application Id'); ?></th>
		<th><?php echo __('Host'); ?></th>
		<th><?php echo __('Database'); ?></th>
		<th><?php echo __('Login'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($application['Database'] as $database): ?>
		<tr>
			<td><?php echo $database['id']; ?></td>
			<td><?php echo $database['application_id']; ?></td>
			<td><?php echo $database['host']; ?></td>
			<td><?php echo $database['database']; ?></td>
			<td><?php echo $database['login']; ?></td>
			<td><?php echo $database['password']; ?></td>
			<td><?php echo $database['modified']; ?></td>
			<td><?php echo $database['created']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'databases', 'action' => 'view', $database['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'databases', 'action' => 'edit', $database['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'databases', 'action' => 'delete', $database['id']), null, __('Are you sure you want to delete # %s?', $database['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Database'), array('controller' => 'databases', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
