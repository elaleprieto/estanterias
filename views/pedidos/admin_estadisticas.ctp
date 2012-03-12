<?php
# Se crean las funciones auxiliares
echo $javascript -> link('https://www.google.com/jsapi');

$datos = '';
foreach($pedidos as $pedido) {
	// data.addRow(['Hermione', new Date(1999,0,1)]); // Add a row with a string and a date value.
	$datos .= 'data.addRow(["'.sprintf("%02d",$pedido[0]['mes']).' - '.$pedido[0]['anio'].'", ' . $pedido[0]['cantidad'] . ']);';
}

$script = "
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
		$datos
	
		// Set chart options
		var options = {
			'title' : 'Pedidos Finalizados',
			'width' : 600,
			'height' : 300,
			'colors' : ['#FFC35B']
		};
	
		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}";

echo $javascript -> codeBlock($script, $options = array('allowCache'=>false))
?>
<div id="chart_div" style="text-align: center"></div>
