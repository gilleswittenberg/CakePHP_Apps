<div class="applications view">
<h2><?php  echo __('Application'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($application['Application']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Absolute Path'); ?></dt>
		<dd>
			<?php echo h($application['DocumentRoot']['absolute_path']); ?>
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
		<li><?php echo $this->Html->link(__('Edit Application'), array('action' => 'edit', $application['Application']['id'])); ?></li>
		<li><?php echo $this->Form->postLink(__('Delete Application'), array('action' => 'delete', $application['Application']['id']), null, __('Are you sure you want to delete # %s?', $application['Application']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Applications'), array('action' => 'index')); ?> </li>
	</ul>
</div>

<div class="related">
<h3><?php echo __('Related ServerAliass'); ?></h3>
	<?php if (!empty($application['ServerAlias'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Domain'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($application['ServerAlias'] as $serverAlias): ?>
		<tr>
			<td><?php echo $serverAlias['id']; ?></td>
			<td><?php echo $serverAlias['domain']; ?></td>
			<td><?php echo $serverAlias['modified']; ?></td>
			<td><?php echo $serverAlias['created']; ?></td>
			<td class="actions">
				<?php //echo $this->Html->link(__('View'), array('controller' => 'server_aliass', 'action' => 'view', $serverAlias['id'])); ?>
				<?php //echo $this->Html->link(__('Edit'), array('controller' => 'server_aliass', 'action' => 'edit', $serverAlias['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'server_aliass', 'action' => 'delete', $serverAlias['id']), null, __('Are you sure you want to delete %s?', $serverAlias['domain'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New ServerAlias'), array('controller' => 'server_aliases', 'action' => 'add', $application['Application']['id'])); ?> </li>
		</ul>
	</div>
</div>

<div class="related">
<h3><?php echo __('Related Databases'); ?></h3>
	<?php if (!empty($application['Database'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Host'); ?></th>
		<th><?php echo __('Database'); ?></th>
		<th><?php echo __('Login'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<!--<th class="actions"><?php echo __('Actions'); ?></th>-->
	</tr>
	<tr>
		<td><?php echo $application['Database']['id']; ?></td>
		<td><?php echo $application['Database']['host']; ?></td>
		<td><?php echo $application['Database']['database']; ?></td>
		<td><?php echo $application['Database']['login']; ?></td>
		<td><?php echo $application['Database']['password']; ?></td>
		<td><?php echo $application['Database']['modified']; ?></td>
		<td><?php echo $application['Database']['created']; ?></td>
		<!--<td class="actions">
			<?php echo $this->Html->link(__('View'), array('controller' => 'databases', 'action' => 'view', $application['Database']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('controller' => 'databases', 'action' => 'edit', $application['Database']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'databases', 'action' => 'delete', $application['Database']['id']), null, __('Are you sure you want to delete # %s?', $application['Database']['id'])); ?>
		</td>-->
	</tr>
	</table>
<?php endif; ?>
</div>
