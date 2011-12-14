<div class="ubicados_index">
	<h2><?php echo 'Ubicación de los artículos';?></h2>
	<h4><?php echo 'Artículos que se encuentran con una ubicación asignada';?></h4>
	<fieldset>
		<legend>
			<?php __('Buscar');?>
		</legend>
		<?php
		# Se cargan las librería JavaScript
		// echo $javascript -> link('prototype');
		// echo $javascript -> link('scriptaculous');

		# Aquí se inicia el Formulario para Buscar
		echo $this -> Form -> create('Ubicado');

		# Se define el campo de búsqueda
		echo $this -> Form -> input('articulo', array(
				'id' => 'articulo',
				'div' => false,
				'label' => false,
				'class' => 'articulo'
		));

		# Se definen las opciones del elemento Ajax
		// $options = array(
		// 'url' => array('action' => 'update_listado'),
		// 'update' => 'listado',
		// 'div' => false
		// );
		//
		// # Se inserta el botón buscar
		// echo $ajax -> submit('Buscar', $options);

		# Se finaliza el formulario
		echo $this -> Form -> end('Buscar');

		# Defino el comportamiento del cuadro de texto
		// echo $ajax -> observeField('articulo', $options);
		?>
	</fieldset>
	<div id="listado">
		<?php echo $this -> element('admin_update_listado');?>
	</div>
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
