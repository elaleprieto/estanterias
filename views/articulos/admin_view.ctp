<?php
# Se carga la librería Jquery
echo $javascript -> link(array('jquery-1.7.1.min','admin_articulos_view'), FALSE);

# Se define la ruta base
echo $javascript -> codeBlock('WEBROOT="' . $this -> Html -> url('/', true) . '"', $options = array('inline' => true));

# Se agregan las CSS
echo $this -> Html -> css('admin_articulos_view');
?>
<div class="detalles">
	<fieldset>
 		<legend>Detalles</legend>
		<dl><?php $i = 0; $class = ' class="altrow"';?>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Codigo'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $articulo['Articulo']['id']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Detalle'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $articulo['Articulo']['detalle']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Unidad'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $articulo['Articulo']['unidad']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Stock'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $articulo['Articulo']['stock']; ?>
				&nbsp;
			</dd>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Pack'); ?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>>
				<?php echo $articulo['Articulo']['pack']; ?>
				&nbsp;
			</dd>
			<dt><?php __('Foto'); ?></dt>
			<dd>
				<?php 
				# Se verifica la existencia de la foto del artículo en el directorio,
				# si no existe se carga la foto "nofoto.png"
				$foto = $this -> Foto -> articulo($articulo['Articulo']['foto']);
				echo $this -> Html -> image($foto, array('class' => 'articulo_view')); ?>
				&nbsp;
			</dd>
		</dl>
	</fieldset>
</div>
<div class="ubicaciones">
	<fieldset>
 		<legend><?= sizeof($ubicaciones) > 1 ? 'Ubicaciones' : 'Ubicación' ?></legend>
		<table cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>Pasillo</th>
					<th>Lado</th>
					<th>Posición</th>
					<th>Altura</th>
					<th>Estado</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($ubicaciones as $ubicacion) {
				?>
				<tr>
					<td><?= $ubicacion['Pasillo']['nombre']; ?></td>
					<td><?= $ubicacion['Pasillo']['lado']; ?></td>
					<td><?= substr($ubicacion['Ubicacion']['posicion'],0,1) == '0' ? substr($ubicacion['Ubicacion']['posicion'],1) : $ubicacion['Ubicacion']['posicion']; ?></td>
					<td><?= $ubicacion['Ubicacion']['altura']; ?></td>
					<td><?= $ubicacion['Ubicado']['estado'] ? 'Temporal' : 'Fijo'; ?></td>
					<td class="actions">
						<?php echo $this->Html->link('Editar', array('controller' => 'Ubicados', 'action' => 'edit', $ubicacion['Ubicado']['id'])); ?>
						<?php echo $this -> Html -> link('Eliminar', array(), array('class'=>'eliminar', 'value'=>$ubicacion['Ubicado']['id']));
						?>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</fieldset>
</div>
