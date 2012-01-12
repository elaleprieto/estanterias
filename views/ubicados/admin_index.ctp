<div class="ubicados_index">
	<h2><?php echo 'Ubicación de los artículos';?></h2>
	<h4><?php echo 'Artículos que se encuentran con una ubicación asignada';?></h4>
	<fieldset>
		<legend>
			<?php __('Buscar');?>
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

		# Se finaliza el formulario
		echo $this -> Form -> end('Buscar');

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
