<?php
# Se carga la librería Jquery
echo $javascript -> link(array(
		'jquery-1.7.1.min',
		'pedidos_admin_edit'
), FALSE);

# Se define la ruta base
echo $javascript -> codeBlock('WEBROOT="../../../"', $options = array(
		'allowCache' => true,
		'safe' => true,
		'inline' => true
));

# Se agregan las CSS
echo $html -> css('pedidos_admin_add');

# Aquí se arma el Formulario de Creación del Pedido
echo $this -> Form -> create('Pedido', array('class' => 'pedidos_add', 'id' => 'formulario'));?>
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
		));
		?>
		<label class="datosCliente">Transporte:</label>
		<?php
		echo $this -> Form -> input('transporte_id', array(
				'class' => 'desplegable',
				'div' => FALSE,
				'label' => FALSE,
		));
		?>
		<label class="datosCliente">Contrarrembolso:</label>
		<?php
		if($this->data['Pedido']['contrarrembolso']) {
		?>
			<input id="contrarrembolso" type="checkbox" class="pedidos_add" name="data[Pedido][contrarrembolso]" value="1" checked="checked"/>
		<?php
		} else {
		?>
			<input id="contrarrembolso" type="checkbox" class="pedidos_add" name="data[Pedido][contrarrembolso]" value="0"/>
		<?php
		}
		?>
		<label class="datosCliente">Cobinpro:</label>
		<?php
		if($this->data['Pedido']['cobinpro']) {
		?>
			<input id="cobinpro" type="checkbox" class="pedidos_add" name="data[Pedido][cobinpro]" value="1" checked="checked"/>
		<?php
		} else {
		?>
			<input id="cobinpro" type="checkbox" class="pedidos_add" name="data[Pedido][cobinpro]" value="0" />
		<?php
		}
		?>
		<br />
		<label id="labelObservaciones" class="datosCliente">Observaciones:</label>
		<?php
		echo $this -> Form -> input('observaciones', array(
				'class' => 'textfield',
				'div' => FALSE,
				'label' => FALSE,
		));
		?>
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
			echo $this -> Form -> button('Agregar Artículo', array(
					'type' => 'button',
					'id' => 'agregar',
					'class' => 'articulo',
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
			<div id="info_cantidad">
			<label class="cantidad_articulos">Cantidad Artículos: </label>
			<div id="cantidad_articulos" class="cantidad_articulos">
				<?php echo sizeof($ordenes); ?>
			</div>
			<div id="serial_articulos" class="invisible">
				<?php echo sizeof($ordenes); ?>
			</div>
		</div>
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
		
	</fieldset>
	<fieldset>
		<legend>
			<?php echo 'Pedido';?>
		</legend>
		<?php
		if($this->data['Pedido']['b']) {
		?>
			<input id="b" type="checkbox" class="pedidos_add" name="data[Pedido][b]" value="1" checked="checked"/>
		<?php
		} else {
		?>
			<input id="b" type="checkbox" class="pedidos_add" name="data[Pedido][b]" value="0"/>
		<?php
		}
		echo $this -> Form -> button('Guardar Pedido', array(
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
			<tbody id='articulos'>
			<?php
				$indice = 0; // indice
				foreach ($ordenes as $orden) {
			?>
					<tr>
						<td class="invisible">
							<input type="hidden" value="<?php echo $orden['Articulo']['id']; ?>"
								name="data[Orden][<?php echo $indice; ?>][id]"
							/>
						</td>
						<td class="invisible">
							<input type="hidden" value="<?php echo $orden['Orden']['cantidad']; ?>"
								name="data[Orden][<?php echo $indice; ?>][Cantidad]"
							/>
						</td>
						<td id="<?php echo 'f'.$orden['Articulo']['id']; ?>">
							<?php
								echo $this->element('get_foto', 
									array('articulo' => $orden));
							?>
						</td>
						<?php
						# se verifica si el artículo es Sin Cargo o no
						if($orden['Orden']['sin_cargo']) {
						?>
							<td id="<?php echo 'c'.$orden['Articulo']['id']; ?>">Sin Cargo</td>
							<td class="invisible"><input type="hidden" name="data[Orden][<?php echo $indice; ?>][SinCargo]" value="1">
</td>
						<?php
						} else {
						?>
							<td id="<?php echo 'c'.$orden['Articulo']['id']; ?>"><?php echo $orden['Articulo']['id']; ?></td>
							<td class="invisible"><input type="hidden" name="data[Orden][<?php echo $indice; ?>][SinCargo]" value="0">
						<?php
						}
						?>
						<td><?php echo $orden['Orden']['cantidad']; ?></td>
						<td id="<?php echo 'u'.$orden['Articulo']['id']; ?>"><?php echo $orden['Articulo']['unidad']; ?></td>
						<td id="<?php echo 'd'.$orden['Articulo']['id']; ?>"><?php echo $orden['Articulo']['detalle']; ?></td>
						
						<!-- Observaciones -->
						<td><?php echo $orden['Orden']['observaciones']; ?></td>
						<td class="invisible"><input type="hidden" name="data[Orden][<?php echo $indice; ?>][Observaciones]" value="<?php echo $orden['Orden']['observaciones']; ?>">

						<!-- Acciones -->
						<td><input class="articulo" type="button" value="Quitar"></td>
						<td>
							<?php
							if($orden['Orden']['estado']) {
							?>
								<input type="checkbox" class="pedidos_add" name="data[Orden][<?php echo $indice; ?>][estado]" value="1" checked="checked"/>
							<?php
							} else {
							?>
								<input type="checkbox" class="pedidos_add" name="data[Orden][<?php echo $indice; ?>][estado]" value="0"/>
							<?php
							}	
							?>
							
						</td>
					</tr>
			<?php	
					$indice++;	
				}
			?>
			</tbody>
		</table>
	</fieldset>
</div>
<?php echo $this -> Form -> end();?>