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
	// posicionPedido = $('#boton_pedido').position();
	$('#menu_transportes').css({
		'margin-left' : ($('#boton_transporte').position().left)
	});
	posicionPedido = $('#boton_pedido').position();
	$('#menu_pedidos').css({
		'margin-left' : (posicionPedido.left)
	});
	posicionArticulo = $('#boton_articulo').position();
	$('#menu_articulos').css({
		'margin-left' : (posicionArticulo.left)
	});

	/********************************************************************
	 * 								Eventos								*
	 *
	 * 		Aquí se registran los eventos para los objetos de la vista	*
	 ********************************************************************/
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
	

	
});
