<?php
// debug($pedidos);
# Se agregan las CSS
echo $html -> css('pedidos_admin_index');
?>
<div class="pedidos_index">
	<h2><?php __('Pedidos Pendientes');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this -> Paginator -> sort('Número', 'Pedido.id');?></th>
			<th><?php echo $this -> Paginator -> sort('cliente_id');?></th>
			<th><?php echo $this -> Paginator -> sort('Fecha', 'created');?></th>
			<th><?php echo $this -> Paginator -> sort('Artículos', 'articulos');?></th>
			<th><?php echo $this -> Paginator -> sort('progreso');?></th>
			<th><?php echo $this -> Paginator -> sort('Transporte', 'Transporte.nombre');?></th>
			<th><?php echo $this -> Paginator -> sort('observaciones');?></th>
			<th class="actions"><?php __('Acciones');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($pedidos as $pedido):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?= $class;?>>
		<td><?= $pedido['Pedido']['id']; ?>&nbsp;</td>
		<td><?= $pedido['Cliente']['nombre']; ?>&nbsp;</td>
		<td><?= $this -> Time -> format($format = 'd/m/Y H:i', $pedido['Pedido']['created']); ?></td>
		<td><?= $pedido['Pedido']['articulos']; ?></td>
		<td><?= sprintf("%.1f %%", $pedido['Pedido']['progreso']); ?></td>
		<td><?= $pedido['Transporte']['nombre']; ?></td>
		<td class="observaciones"><?= $pedido['Pedido']['observaciones']; ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__('Editar', true), array('controller' => 'pedidos', 'action' => 'edit', $pedido['Pedido']['id'])); ?>
			<?php echo $this->Html->link(__('Preparar', true), array('admin' => FALSE, 'controller' => 'ordenes', 'action' => 'preparar', $pedido['Pedido']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página %page% de %pages%, mostrando %current% registros de un total de %count%, empezando en el registro %start%, terminando en %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('anterior', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>