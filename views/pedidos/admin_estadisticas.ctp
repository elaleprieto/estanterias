<?php
# Se crean las funciones auxiliares
echo $javascript -> link('https://www.google.com/jsapi');

# Inicialización de variables
$datos_pedidos_mes = '';
$datos_productos_pedido = '';

#####################################################################################
# 						Gráfico Pedidos Finalizados									#
#####################################################################################
foreach ($pedidos_mes as $pedido) {
	// data.addRow(['Hermione', new Date(1999,0,1)]); // Add a row with a string and a date value.
	$datos_pedidos_mes .= 'data.addRow(["' . sprintf("%02d", $pedido[0]['mes']) . ' - ' . $pedido[0]['anio'] . '", ' . $pedido[0]['cantidad'] . ']);';
}

$script_pedidos_mes = "
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1.0', {
		'packages' : ['corechart']
	});
	
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);
	
	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart() {
	
		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Topping');
		data.addColumn('number', 'Cantidad');
		//data.addRows([['Mushrooms', 3], ['Onions', 1], ['Olives', 1], ['Zucchini', 1], ['Pepperoni', 2]]);
		$datos_pedidos_mes
	
		// Set chart options
		var options = {
			'title' : 'Pedidos Finalizados',
			'width' : 800,
			'height' : 500,
			'colors' : ['#FFC35B'],
			vAxis: {title: 'Cantidad de Pedidos', titleTextStyle: {color: 'red'}},
			hAxis: {title: 'Mes - Año', titleTextStyle: {color: 'red'}}
		};
	
		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.ColumnChart(document.getElementById('pedidos_mes_div'));
		chart.draw(data, options);
	}";

echo $javascript -> codeBlock($script_pedidos_mes, $options = array('allowCache' => false));

#####################################################################################
# 					Gráfico Cantidad de Productos por Pedido						#
#####################################################################################
foreach ($productos_pedido as $producto) {
	// data.addRow(['Hermione', new Date(1999,0,1)]); // Add a row with a string and a date value.
	$datos_productos_pedido .= 'data.addRow(["' . sprintf("%02d", $producto[0]['productos']) . '", ' . $producto[0]['cantidad'] . ']);';
}

$script_productos_pedido = "
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1.0', {
		'packages' : ['corechart']
	});
	
	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);
	
	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart() {
	
		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Cantidad de Pedidos');
		data.addColumn('number', 'Cantidad de Productos');
		//data.addRows([['Mushrooms', 3], ['Onions', 1], ['Olives', 1], ['Zucchini', 1], ['Pepperoni', 2]]);
		$datos_productos_pedido
	
		// Set chart options
		var options = {
			'title' : 'Cantidad de Productos por Pedido',
			'width' : 800,
			'height' : 500,
			'colors' : ['#FFC35B'],
			vAxis: {title: 'Cantidad de Productos', titleTextStyle: {color: 'red'}},
			hAxis: {title: 'Cantidad de Pedidos', titleTextStyle: {color: 'red'}}
			
		};
	
		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.ColumnChart(document.getElementById('productos_pedido_div'));
		chart.draw(data, options);
	}";

echo $javascript -> codeBlock($script_productos_pedido, $options = array('allowCache' => false));
?>
<div id="pedidos_mes_div" style="text-align: center"></div>
<div id="productos_pedido_div" style="text-align: center"></div>
