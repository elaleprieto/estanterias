<div class="ubicados_index">
	<fieldset>
		<legend>
			<?php __('Buscar Artículos Ubicados');?>
		</legend>
		<?php
		# Aquí se inicia el Formulario para Buscar
		echo $this -> Form -> create('Ubicado');

		# Se define el campo de búsqueda
		echo $this -> Form -> input('articulo', array(
				'id' => 'articulo',
				'div' => false,
				'label' => false,
				'class' => 'articulo'
		));
		echo $this -> Form -> submit('Buscar', array(
				'div' => false,));

		# Se finaliza el formulario
		echo $this -> Form -> end();

		?>
	</fieldset>
	<div id="listado">
		<?php echo $this -> element('update_listado');?>
	</div>
</div>
