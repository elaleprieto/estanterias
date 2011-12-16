<?php
# Se carga la librería Jquery
echo $javascript -> link(array(
		'jquery-1.7.1.min',
		'pedidos_admin_add'
), FALSE);

# Se define la ruta base
echo $javascript -> codeBlock('WEBROOT="../../"', $options = array(
		'allowCache' => true,
		'safe' => true,
		'inline' => true
));

# Se agregan las CSS
echo $html -> css('pedidos_add');

# Aquí se arma el Formulario de Creación del Pedido
echo $this -> Form -> create('Pedido', array('class' => 'pedidos_add', 'id' => 'formulario'));?>
<div class="pedidos_add">
	<fieldset>
		<legend>
			<?php echo 'Datos del Cliente';?>
		</legend>
		<label class="datosCliente">Cliente:</label>
		<?php
		echo $this -> Form -> input('cliente_id', array(
				'class' => 'articulo',
				'div' => FALSE,
				'label' => FALSE,
		));
		?>
		<label class="datosCliente">Transporte:</label>
		<?php
		echo $this -> Form -> input('transporte_id', array(
				// 'class' => 'articulo',
				'div' => FALSE,
				'label' => FALSE,
		));
		?>
		<label class="datosCliente">Contrarrembolso:</label>
		<input id="contrarrembolso" type="checkbox" class="pedidos_add" name="data[Pedido][contrarrembolso]" value="0"/>
		<label class="datosCliente">Cobinpro:</label>
		<input id="cobinpro" type="checkbox" class="pedidos_add" name="data[Pedido][cobinpro]" value="0"/>
	</fieldset>
	<fieldset>
		<legend>
			<?php echo 'Articulos';?>
		</legend>
		<div>
			<?php
			echo $this -> Form -> input('buscar', array(
					'id' => 'busqueda',
					'div' => FALSE,
					'label' => 'Buscar:',
					'autocomplete' => 'off'
			));
			echo $this -> Form -> button('<< Anterior', array(
					'type' => 'button',
					'id' => 'anterior'
			));
			echo $this -> Form -> button('Siguiente >>', array(
					'type' => 'button',
					'id' => 'siguiente'
			));
			?>
		</div>
		<div class="lista">
			<?php
			echo $this -> Form -> input('articulos', array(
					'id' => 'lista',
					'div' => FALSE,
					'label' => FALSE,
					'type' => 'select',
					'size' => '5'
			));
			?>
		</div>
		<div class="atributos">
			<div class="atributo">
				<label class="atributo">Unidad:</label>
				<label id="unidad">C/U</label>
			</div>
			<div class="atributo">
				<label class="atributo">Cantidad:</label>
				<?php
				echo $this -> Form -> input('cantidad', array(
						'id' => 'cantidad',
						'div' => FALSE,
						'label' => FALSE,
						'autocomplete' => 'off',
				));
				?>
			</div>
			<br />
			<div class="atributo">
				<label class="atributo">Sin Cargo:</label>
				<input id="sin_cargo_ckeckbox" type="checkbox" class="pedidos_add"/>
				<?php
				echo "<input type='button' id='agregar' class='articulo' value='Agregar Artículo' />";
				echo $javascript -> event('agregar', 'click', 'articulos_actualizar()');
				?>
			</div>
		</div>
		<div id="info_cantidad">
			<label class="cantidad_articulos">Cantidad Artículos: </label>
			<div id="cantidad_articulos" class="cantidad_articulos">
				0
			</div>
			<div id="serial_articulos" class="invisible">
				0
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>
			<?php echo 'Pedido';?>
		</legend>
		<input id="b" type="checkbox" class="pedidos_add" name="data[Pedido][b]" value="0"/>
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
					<th><?php echo 'Acciones';?></th>
				</tr>
			</thead>
			<tbody id='articulos'></tbody>
		</table>
	</fieldset>
</div>
<?php echo $this -> Form -> end();?>