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

	// Recargar la página cada 5 minutos
	minutos = 5 * 60 * 1000;
	setTimeout("location.reload()", minutos);

	/********************************************************************
	 * 								Eventos								*
	 *
	 * 		Aquí se registran los eventos para los objetos de la vista	*
	 ********************************************************************/
	$('.arrow_down').click(function(e) {
		setPrioridadDown(this);
	});
	$('.arrow_up').click(function(e) {
		setPrioridadUp(this);
	});
});
/********************************************************************
 * 								Funciones							*
 *
 *				 		Aquí se escriben las funciones				*
 ********************************************************************/
function setPrioridadDown(elemento) {
	// Fila del Pedido que se está modificando
	var filaPedido = $(elemento).parents('tr');
	// Nueva Prioridad del Pedido que se está modificando
	var prioridadNueva = parseInt($(elemento).prevAll('label').text()) - 1;
	// Id del Pedido que se está modificando la prioridad
	var id = $(elemento).parents('tr').children('td.id').text();

	// Fila Posterior
	var filaPosterior = $(elemento).parents('tr').next('tr');
	// Prioridad del Pedido en la fila anterior
	var prioridadPosterior = $(filaPosterior).find("td > label").text();

	// Imagen para avisar al usuario que se está llevando a cabo la actualización
	var imagen = $('<img>').attr('src', WEBROOT + 'img/load.gif').attr('class', 'load');
	$(elemento).prevAll('label').html(imagen);
	filaPedido.toggleClass('modificada', true);

	// Se persiste la prioridad del Pedido
	$.get(WEBROOT + "admin/pedidos/set_prioridad/" + id + "/" + prioridadNueva, function() {
		// Se escribe la Prioridad Actual en la pantalla
		$(elemento).prevAll('label').text(prioridadNueva);
		filaPedido.toggleClass('modificada', false);
	});
	
	// Si la prioridad del Pedido en la fila posterior es mayor que la nueva,
	// se toma el Pedido modificado y se lo compara con los anteriores para bajarlo en la tabla.
	if(prioridadPosterior != '' && prioridadNueva < prioridadPosterior) {
		fila = filaPedido.detach();
		filaAux = $(filaPosterior).next('tr');
		prioridadAux = $(filaAux).find("td > label").text();

		while(prioridadAux != '' && prioridadNueva < prioridadAux) {
			filaPosterior = filaAux;
			filaAux = $(filaPosterior).next('tr');
			prioridadAux = $(filaAux).find("td > label").text();
		}
		$(filaPosterior).after(fila);
	}
	

}

function setPrioridadUp(elemento) {
	// Fila del Pedido que se está modificando
	var filaPedido = $(elemento).parents('tr');
	// Nueva Prioridad del Pedido que se está modificando
	var prioridadNueva = parseInt($(elemento).prevAll('label').text()) + 1;
	// Id del Pedido que se está modificando la prioridad
	var id = $(elemento).parents('tr').children('td.id').text();

	// Fila Anterior
	filaAnterior = $(elemento).parents('tr').prev('tr');
	// Prioridad del Pedido en la fila anterior
	var prioridadAnterior = $(filaAnterior).find("td > label").text();

	// Imagen para avisar al usuario que se está llevando a cabo la actualización
	var imagen = $('<img>').attr('src', WEBROOT + 'img/load.gif').attr('class', 'load');
	$(elemento).prevAll('label').html(imagen);
	filaPedido.toggleClass('modificada', true);

	// Se persiste la prioridad del Pedido
	$.get(WEBROOT + "admin/pedidos/set_prioridad/" + id + "/" + prioridadNueva, function() {
		// Se escribe la Prioridad Actual en la pantalla
		$(elemento).prevAll('label').text(prioridadNueva);
		filaPedido.toggleClass('modificada', false);
	});

	// Si la prioridad del Pedido en la fila anterior es menor que la nueva,
	// se toma el Pedido modificado y se lo compara con los anteriores para subirlo en la tabla.
	if(prioridadAnterior != '' && prioridadNueva > prioridadAnterior) {
		fila = filaPedido.detach();
		filaAux = $(filaAnterior).prev('tr');
		prioridadAux = $(filaAux).find("td > label").text();

		while(prioridadAux != '' && prioridadNueva > prioridadAux) {
			filaAnterior = filaAux;
			filaAux = $(filaAnterior).prev('tr');
			prioridadAux = $(filaAux).find("td > label").text();
		}
		$(filaAnterior).before(fila);
	}
}

/**
 * ordenarPrioridad(): ordena los pedidos según el número de prioridad.
 */
function ordenarPrioridad() {
	$('#pedidos > tbody > tr > td > label').each(function(index) {
		console.info(this);
	});
}