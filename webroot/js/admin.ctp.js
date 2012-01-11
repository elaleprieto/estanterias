/**
 * @author ale
 */
$(document).ready(function() {
	/********************************************************************
	 *						Variables Globales							*
	 ********************************************************************/

	/********************************************************************
	 *					Inicialización de Objetos						*
	 ********************************************************************/
	$('#menu_pedidos').css({
		'margin-left' : ($('#boton_pedido').position().left)
	});
	$('#menu_articulos').css({
		'margin-left' : ($('#boton_articulo').position().left)
	});
	$('#menu_clientes').css({
		'margin-left' : ($('#boton_cliente').position().left)
	});
	$('#menu_transportes').css({
		'margin-left' : ($('#boton_transporte').position().left)
	});

	/********************************************************************
	 * 								Eventos								*
	 *
	 * 		Aquí se registran los eventos para los objetos de la vista	*
	 ********************************************************************/
	
	/* Botón Pedido y Menú Pedido */
	$('#boton_pedido,#menu_pedidos').hover(function(e) {
		$('#menu_pedidos').css({
			display : "block"
		});
	}, function(e) {
		$('#menu_pedidos').css({
			display : "none"
		});
	});
	
	/* Botón Artículo y Menú Artículo */
	$('#boton_articulo,#menu_articulos').hover(function(e) {
		$('#menu_articulos').css({
			display : "block"
		});
	}, function(e) {
		$('#menu_articulos').css({
			display : "none"
		});
	});
	
	/* Botón Cliente y Menú Cliente */
	$('#boton_cliente,#menu_clientes').hover(function(e) {
		$('#menu_clientes').css({
			display : "block"
		});
	}, function(e) {
		$('#menu_clientes').css({
			display : "none"
		});
	});
	
	/* Botón Transporte y Menú Transporte */
	$('#boton_transporte,#menu_transportes').hover(function(e) {
		$('#menu_transportes').css({
			display : "block"
		});
	}, function(e) {
		$('#menu_transportes').css({
			display : "none"
		});
	});

	
});
