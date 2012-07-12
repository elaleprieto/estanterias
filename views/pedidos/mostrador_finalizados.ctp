<?php //debug($pedidos);?>
<div class="mostrador">
	<h2><?php __('Pedidos Finalizados');?></h2>
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this -> Paginator -> sort('Fecha', 'created');?></th>
			<th><?php echo $this -> Paginator -> sort('cliente_id');?></th>
			<th><?php echo $this -> Paginator -> sort('Transporte', 'Transporte.nombre');?></th>
			<th><?php echo $this -> Paginator -> sort('Observaciones', 'Pedido.observaciones');?></th>
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
		<tr<?php echo $class;?>>
			<td><?php
			# Fecha de creación formateada
			echo $this -> Time -> format($format = 'd/m/y', $pedido['Pedido']['created']);
			?>&nbsp; </td>
			<td><?php echo $pedido['Cliente']['nombre'];?>&nbsp;</td>
			<td><?= $pedido['Transporte']['nombre']?></td>
			<td><?= $pedido['Pedido']['observaciones']?></td>
			
			<!------------------------------------------------------------------------------------>
			<!-- 									Acciones									-->
			<!------------------------------------------------------------------------------------>
			<td class="acciones">
				<?php echo $this -> Html -> link($this -> Html -> image("edit.png", array("alt" => "Editar", "title" => "Editar")), array(
					'controller' => 'pedidos',
					'action' => 'edit',
					$pedido['Pedido']['id']
				), array('escape' => false));
				?>
				<?php echo $this -> Html -> link($this -> Html -> image("pendiente.png", array("alt" => "Pendiente", "title" => "Pendiente")), array(
						'controller' => 'pedidos',
						'action' => 'index',
						$pedido['Pedido']['id']
					), array('escape' => false), sprintf('¿Devolver a Pedidos Pendientes el pedido de %s?', $pedido['Cliente']['nombre']));
				?>
				<?php echo $this -> Html -> link($this -> Html -> image("print.png", array("alt" => "Imprimir", "title" => "Imprimir")), array(
							'action' => 'imprimir',
							$pedido['Pedido']['id']
					), array('escape' => false, 'target' => '_blank'));
				?>
				<?php echo $this -> Html -> link($this -> Html -> image("controlado.gif", array("alt" => "Controlado", "title" => "Controlado")), array(
						'controller' => 'pedidos',
						'action' => 'controlados',
						$pedido['Pedido']['id']
					), array('escape' => false), sprintf('¿Ha sido controlado el pedido de %s?', $pedido['Cliente']['nombre']));
				?>
			</td>
			<!------------------------------------------------------------------------------------>
			<!-- 								Fin	Acciones									-->
			<!------------------------------------------------------------------------------------>
			</tr> <?php endforeach;?>
	</table>
	<p>
		<?php
		echo $this -> Paginator -> counter(array('format' => __('Página %page% de %pages%, mostrando %current% registros de un total de %count%, empezando en el registro %start%, terminando en %end%', true)));
		?>
	</p>
	<div class="paging">
		<?php echo $this -> Paginator -> prev('<< ' . __('anterior', true), array(), null, array('class' => 'disabled'));?>
		| 	<?php echo $this -> Paginator -> numbers();?>
		|
		<?php echo $this -> Paginator -> next(__('siguiente', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>