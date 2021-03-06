<div class="provincias view">
<h2><?php  __('Provincia');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $provincia['Provincia']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nombre'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $provincia['Provincia']['nombre']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $provincia['Provincia']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $provincia['Provincia']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Provincia', true), array('action' => 'edit', $provincia['Provincia']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Provincia', true), array('action' => 'delete', $provincia['Provincia']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $provincia['Provincia']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Provincias', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Provincia', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Localidades', true), array('controller' => 'localidades', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Localidad', true), array('controller' => 'localidades', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Localidades');?></h3>
	<?php if (!empty($provincia['Localidad'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Nombre'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th><?php __('Provincia Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($provincia['Localidad'] as $localidad):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $localidad['id'];?></td>
			<td><?php echo $localidad['nombre'];?></td>
			<td><?php echo $localidad['created'];?></td>
			<td><?php echo $localidad['modified'];?></td>
			<td><?php echo $localidad['provincia_id'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'localidades', 'action' => 'view', $localidad['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'localidades', 'action' => 'edit', $localidad['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'localidades', 'action' => 'delete', $localidad['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $localidad['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Localidad', true), array('controller' => 'localidades', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
