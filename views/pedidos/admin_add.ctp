<?php
# Se carga la librería Jquery
echo $javascript -> link(array('jquery-1.7.1.min','pedidos_admin_add_edit','pedidos_admin_add'), FALSE);

# Se define la ruta base
echo $javascript -> codeBlock('WEBROOT="'.$this -> Html-> url('/', true).'"');

# Se agregan las CSS
echo $html -> css('pedidos_admin_add');

# Aquí se arma el Formulario de Creación del Pedido
echo $this -> Form -> create('Pedido', array(
		'class' => 'pedidos_add',
		'id' => 'formulario'
));
?>
<div class="pedidos_add">
	<?php echo $this -> element('pedidos_datos'); ?>
	<?php echo $this -> element('pedidos_articulos'); ?>
	<fieldset>
		<legend>
			<?php echo 'Pedido';?>
		</legend>
		<input id="presupuesto" type="checkbox" class="pedidos_add" name="data[Pedido][b]" value="0"/>
		<?php
		echo $this -> Form -> button('Crear Pedido', array(
				'class' => 'articulo',
				'type' => 'button',
				'div' => FALSE,
				'id' => 'crear',
		));
		?>
		<table id="ordenes" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo 'Foto';?></th>
					<th><?php echo 'Codigo';?></th>
					<th><?php echo 'Cantidad';?></th>
					<th><?php echo 'Unidad';?></th>
					<th><?php echo 'Articulo';?></th>
					<th><?php echo 'Observ.';?></th>
					<th><?php echo 'Acciones';?></th>
				</tr>
			</thead>
			<tbody id='articulos'></tbody>
		</table>
	</fieldset>
</div>
<div id="serial_articulos" class="invisible">0</div>
<?php echo $this -> Form -> end();?>