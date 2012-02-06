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
	</div>
</fieldset>