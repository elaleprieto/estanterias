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
echo $html -> css('pedidos_admin_add');

# Aquí se arma el Formulario de Creación del Pedido
echo $this -> Form -> create('Pedido', array(
		'class' => 'pedidos_add',
		'id' => 'formulario'
));
?>
<div class="pedidos_add">
	<fieldset>
		<legend>
			<?php echo 'Datos del Pedido';?>
		</legend>
		<label id="labelCliente" class="datosCliente">Cliente:</label>
		<?php
		echo $this -> Form -> input('cliente_id', array(
				'class' => 'articulo desplegable',
				'div' => FALSE,
				'label' => FALSE,
				'id' => 'cliente'
		));
		?>
		<label id="labelObservaciones" class="datosCliente">Observaciones:</label>
		<?php
		echo $this -> Form -> input('observaciones', array(
				'class' => 'textfield',
				'div' => FALSE,
				'label' => FALSE,
		));
		?>
		<br />
		<label class="datosCliente">Transporte:</label>
		<?php
		echo $this -> Form -> input('transporte_id', array(
				'class' => 'desplegable',
				'div' => FALSE,
				'label' => FALSE,
				'empty' => '(elija Transporte)',
				'id' => 'transporte'
		));
		?>
		<label class="datosCliente">Contrarrembolso:</label>
		<input id="contrarrembolso" type="checkbox" class="pedidos_add" name="data[Pedido][contrarrembolso]" value="0"/>
		<label class="datosCliente">Cobinpro:</label>
		<input id="cobinpro" type="checkbox" class="pedidos_add" name="data[Pedido][cobinpro]" value="1" />
		<div id="load_cliente">
			<?php echo $this -> Html -> image('load.gif', array('class' => 'load'));?>
			Cargando Cliente...
		</div>
	</fieldset>
	<fieldset>
		<legend>
			<?php echo 'Artículos';?>
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
			echo $this -> Form -> input('codigo', array(
					'id' => 'codigo',
					'div' => FALSE,
					'label' => 'Código:',
					'autocomplete' => 'off'
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
					'size' => '8'
			));
			?>
		</div>
		<div class="atributos">
			<div class="atributo unidad">
				<label class="atributo">Unidad:</label>
				<label id="unidad">C/U</label>
			</div>
			<div class="atributo">
				<label class="atributo">Stock:</label>
				<label id="stock">0</label>
			</div>
			<div class="atributo">
				<label class="atributo">Pack:</label>
				<label id="pack">0</label>
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
			</div>
			<br />
		</div>
		<div class="observaciones">
			<label class="atributo">Observaciones:</label>
			<?php
			echo $this -> Form -> textarea('articuloObservaciones', array(
					'id' => 'articuloObservaciones',
					'div' => FALSE,
					'label' => "Observaciones",
			));
			?>
		</div>
		<div id="info_cantidad">
			<?php
			echo $this -> Form -> button('Agregar Artículo', array(
					'type' => 'button',
					'id' => 'agregar',
					'class' => 'articulo',
			));
			?>
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
<?php echo $this -> Form -> end();?>