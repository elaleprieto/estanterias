$(document).ready(function() {
	/********************************************************************
	 *						Variables Globales							*
	 ********************************************************************/
	var seleccionado = 0;
	var submit = false;
	var opciones = new Array();
	/********************************************************************
	 *					Inicialización de Objetos						*
	 ********************************************************************/
	deshabilitarBotones();
	limpiarCampos();

	/********************************************************************
	 * 								Eventos								*
	 *
	 * 		Aquí se registran los eventos para los objetos de la vista	*
	 ********************************************************************/
	$('#busqueda').keyup(function(e) {
		//37=>Left Key,38=>Up Key,39=>Right Key,40=>Down Key
		if(e.which == 39 || e.which == 40) {
			avanzar();
		} else if(e.which == 37 || e.which == 38) {
			retroceder();
		} else {
			buscar();
		}
	});
	$('#busqueda').keydown(function(e) {
		if(e.which == 9) {
			$('#lista').focus();
		}
	});
	$('#anterior').click(function() {
		retroceder();
	});
	$('#siguiente').click(function() {
		avanzar();
	});
	$('#agregar').click(function() {
		articulosActualizar();
	});
	$('#cantidad, #lista').keypress(function(e) {
		if(e.which == 13) {
			articulosActualizar();
		}
	});
	$('#lista option').click(function() {
		setLabelUnidad($('#lista option:selected').val());
		setLabelStock($('#lista option:selected').val());
	});
	$('#formulario').submit(function(e) {
		// se envía el formulario sólo se se ha presionadao del botón #crear
		return submit;
	});
	$('#crear').click(function(e) {
		// se envía el formulario
		submit = true;
		$('#formulario').submit();
	});
	$('#b,#contrarrembolso,#cobinpro').click(function() {
		if($(this).attr('checked')) {
			$(this).val(1);
		} else {
			$(this).val(0);
		}
	});
	/********************************************************************
	 * 								Funciones							*
	 ********************************************************************/
	/**
	 * buscar(): Obtiene un conjunto de objetos de la lista de artículos,
	 * los cuales contienen la palabra ingresada por el usuario en algún lugar del texto.
	 * @call: el usuario ingresa una palabra en el campo de búsqueda.
	 */
	function buscar() {
		deshabilitarBotones();
		var texto = $('#busqueda').val().toUpperCase();
		var palabras = texto.split(" ");
		var contiene = "";

		for(var i = palabras.length - 1; i >= 0; i--) {
			if(palabras[i] != "") {
				contiene += ':contains(' + palabras[i] + ')';
			};
		}
		opciones = $('#lista option' + contiene);
		seleccionado = 0;

		if(opciones.length > 0) {
			$(opciones[0]).attr('selected', 'selected');
			setLabelUnidad($('#lista option:selected').val());
			setLabelStock($('#lista option:selected').val());
			habilitarBotones();
		}
	}

	/**
	 * avanzar(): Selecciona el siguiente resultado en la lista de artículos. Funciona en conjunto con la función "buscar()".
	 * @call: el usuario presiona el botón "Siguiente".
	 */
	function avanzar() {
		if(seleccionado >= opciones.length - 1) {
			seleccionado = 0;
		} else {
			seleccionado++;
		}
		$(opciones[seleccionado]).attr('selected', 'selected');
		setLabelUnidad($('#lista option:selected').val());
		setLabelStock($('#lista option:selected').val());
	}

	/**
	 * retroceder(): Selecciona el resultado anterior en la lista de artículos. Funciona en conjunto con la función "buscar()".
	 * @call: el usuario presiona el botón "Anterior".
	 */
	function retroceder() {
		if(seleccionado == 0) {
			seleccionado = opciones.length - 1;
		} else {
			seleccionado--;
		}
		$(opciones[seleccionado]).attr('selected', 'selected');
		setLabelUnidad($('#lista option:selected').val());
		setLabelStock($('#lista option:selected').val());
	}

	/**
	 * deshabilitarBotones(): Se utiliza en conjunto con habilitarBotones() para permitir un mayor control de la acciones del usuario.
	 * @call: al inicializar la página.
	 * @call: el usuario escribe en el campo de búsqueda.
	 */
	function deshabilitarBotones() {
		$('#anterior').attr('disabled', 'disabled');
		$('#siguiente').attr('disabled', 'disabled');
	}

	/**
	 * habilitarBotones(): Se utiliza en conjunto con deshabilitarBotones() para permitir un mayor control de la acciones del usuario.
	 * @call: Se llama cuando el campo de búsqueda contiene una palabra que arroja resultados en la lista de artículos
	 */
	function habilitarBotones() {
		$('#anterior').removeAttr('disabled');
		$('#siguiente').removeAttr('disabled');
	}

	/**
	 * limpiarCampos(): Setea en blanco los valores de los campos al inicializar.
	 */
	function limpiarCampos() {
		$('#busqueda').val('');
		$('#cantidad').val('');
		$('#cobinpro').attr('checked', 'checked');
	}

	/**
	 * articulosActualizar() se llama para agregar el artículo a la lista.
	 */
	function articulosActualizar() {
		// se obtiene el artículo seleccionado
		var articulo_id = $('#lista option:selected').val();
		// se obtiene la cantidad
		var cantidad = $("#cantidad").val();

		// se verifica que el artículo_id esté definido
		if(articulo_id > 0) {
			// se verifica que la cantidad esté definida
			if(cantidad && !(isNaN(cantidad))) {
				/* definición de variables */
				var condicion = existeEnLista(articulo_id);

				if(!(condicion)) {
					/* definición de variables */
					// cuenta secuencial por si se eliminan artículos, siempre se incrementa
					var indice = parseInt($('#serial_articulos').html());
					var sinCargo = 0;

					// creo la fila y columnas
					fila = $('<tr>');

					// columna Foto
					foto = $('<img>').attr("class", "ubicados_index").attr("src", WEBROOT + "img/articulos/nofoto.png");
					columnaFoto = $('<td>').attr("id", "f" + articulo_id).append(foto);
					getFoto(articulo_id, columnaFoto);

					// // columna Id (oculta)
					inputId = $('<input />').attr("type", "hidden").attr("name", "data[Orden][" + indice + "][id]").attr("value", articulo_id);
					columnaId = $('<td>').attr("class", "invisible").append(inputId);

					// columna Cantidad (oculta)
					inputCantidadHidden = $('<input />').attr("type", "hidden").attr("name", "data[Orden][" + indice + "][Cantidad]").attr("value", cantidad);
					columnaCantidadHidden = $('<td>').attr("class", "invisible").append(inputCantidadHidden);

					// columna Codigo
					columnaCodigo = $('<td>').attr("id", "c" + articulo_id).append(articulo_id);
					if($('#sin_cargo_ckeckbox').attr('checked')) {
						columnaCodigo.text('Sin Cargo');
					}

					// columna Cantidad
					columnaCantidad = $('<td>').append(cantidad);

					// columna Unidad
					columnaUnidad = $('<td>').attr("id", "u" + articulo_id).append($('#unidad').html());
					getUnidad(articulo_id, columnaUnidad);

					// columna Artículo
					columnaArticulo = $('<td>').attr("id", "d" + articulo_id);
					columnaArticulo.append($('#lista option:selected').html());
					
					// columna Observaciones
					columnaObservaciones = $('<td>');
					columnaObservaciones.append($('#articuloObservaciones').val());
					
					// columna Observaciones (oculta)
					inputObservacionesHidden = $('<input />').attr("type", "hidden").attr("name", "data[Orden][" + indice + "][Observaciones]").attr("value", $('#articuloObservaciones').val());
					columnaObservacionesHidden = $('<td>').attr("class", "invisible").append(inputObservacionesHidden);

					// columna Acciones
					quitar = $('<input />').attr("type", "button").attr("class", "articulo").attr("value", "Quitar");
					$(quitar).click(function() {
						quitarArticulo($(this));
					});
					columnaAcciones = $('<td>').append(quitar);

					// columna para inidicar Artículo Sin Cargo o No (oculta)
					if($('#sin_cargo_ckeckbox').attr('checked')) {
						sinCargo = 1;
					}
					inputSinCargo = $('<input />').attr("type", "hidden").attr("name", "data[Orden][" + indice + "][SinCargo]").attr("value", sinCargo);
					columnaSinCargo = $('<td>').attr("class", "invisible").append(inputSinCargo);

					// agrego las columnas a la fila
					fila.append(columnaId);
					fila.append(columnaCantidadHidden);
					fila.append(columnaFoto);
					fila.append(columnaCodigo);
					fila.append(columnaCantidad);
					fila.append(columnaUnidad);
					fila.append(columnaArticulo);
					fila.append(columnaObservaciones);
					fila.append(columnaObservacionesHidden);
					fila.append(columnaAcciones);
					fila.append(columnaSinCargo);

					// agrego la fila a la tabla en la primera posición
					$("#ordenes > tbody").hide().prepend(fila).fadeIn(1000);

					// se suma 1(uno) a la cantidad de artículos del pedido y al serial.
					$('#cantidad_articulos').html(parseInt($('#cantidad_articulos').html()) + 1);
					$('#serial_articulos').html(parseInt($('#serial_articulos').html()) + 1);
				} else {
					// si el artículo ya se encuentra en la tabla,
					// se le corrige la cantidad, se le suma la nueva.
					corregirCantidad(articulo_id, cantidad);
				}
				$('#lista').focus();
			} else {
				alert("Verifique la Cantidad.");
				$('#cantidad').focus();
			}
		} else {
			alert("Verifique el Artículo.");
		}
	}

	/**
	 * existeEnLista(articulo_id) recorre la lista de artículos
	 * buscando si el artículo pasado como parámetro
	 * ya se encuentra cargado.
	 */
	function existeEnLista(articulo_id) {
		var codigo = "c" + articulo_id;
		var existe = false;

		// se busca si existe una celda (td) en la tabla cuya ID sea el Codigo
		// y contenga la leyenda Sin Cargo o el articulo_id según el artículo que se está agregando.
		if(($('#sin_cargo_ckeckbox').attr('checked'))) {
			// si está marcado el campo Sin Cargo
			celda = $('td[id=' + codigo + ']:contains("Sin Cargo")');
		} else {
			// si no está marcado el campo Sin Cargo
			celda = $('td[id=' + codigo + ']:contains("' + articulo_id + '")');
		}

		// aquí se verifica si se encontró alguna coincidencia.
		if(celda.length > 0) {
			existe = true;
		}
		return existe;
	}

	/**
	 * corregirCantidad(articulo_id, cantidad) recorre la lista de artículos buscando el artículo pasado como parámetro
	 * y suma la cantidad pasada como parámetro.
	 * Esta función verifica la existencia del artículo, pero igualmente debería llamarse
	 * después de la función existeEnLista(articulo_id) para evitar sorpresas o mal funcionamiento.
	 */
	function corregirCantidad(articulo_id, cantidad) {
		// var filas = ($("articulos").childElements());
		var codigo = "c" + articulo_id;
		var existe = false;

		if(!(isNaN(cantidad))) {
			// se busca si existe una celda (td) en la tabla cuya ID sea el Codigo
			// y contenga la leyenda Sin Cargo o el articulo_id según el artículo que se está agregando.
			if(($('#sin_cargo_ckeckbox').attr('checked'))) {
				// si está marcado el campo Sin Cargo
				celda = $('td[id=' + codigo + ']:contains("Sin Cargo")');
			} else {
				// si no está marcado el campo Sin Cargo
				celda = $('td[id=' + codigo + ']:contains("' + articulo_id + '")');
			}

			// aquí se verifica si se encontró alguna coincidencia.
			if(celda.length > 0) {
				// se obntienen todas las columnas de la fila
				columnas = $(celda).parent().children();

				// la columna 4 es la cantidad
				$(columnas[4]).html(parseFloat($(columnas[4]).html()) + parseFloat(cantidad));
				

				// la columna 1 es el campo oculto de la cantidad
				// el campo oculto de la cantidad es el que se toma para guardar en la BD
				$(columnas[1]).children().val(parseFloat($(columnas[1]).children().val()) + parseFloat(cantidad));
			}
		}
	}

	/**
	 * 	getUnidad se encarga de hacer la petición de la unidad del artículo
	 *	y colocarlo en la fila correspondiente.
	 */
	function getUnidad(id, columnaUnidad) {
		if(id && columnaUnidad) {
			$(columnaUnidad).load(WEBROOT + "articulos/get_unidad/" + id);
		}
	}

	/**
	 * 	setLabelUnidad setea la etiqueta Unidad para proporcionar información al usuario.
	 */
	function setLabelUnidad(id) {
		if(id) {
			imagen = $('<img>').attr('src', WEBROOT + 'img/load.gif').attr('class', 'load');
			$('#unidad').html(imagen);
			$('#unidad').load(WEBROOT + "articulos/get_unidad/" + id);
		}
	}
	
	/**
	 * 	setLabelStock setea la etiqueta Stock para proporcionar información al usuario.
	 */
	function setLabelStock(id) {
		if(id) {
			imagen = $('<img>').attr('src', WEBROOT + 'img/load.gif').attr('class', 'load');
			$('#stock').html(imagen);
			$('#stock').load(WEBROOT + "articulos/get_stock/" + id);
		}
	}

	/**
	 *	getFoto se encarga de hacer la petición de la foto del artículo
	 *	y colocarlo en la fila correspondiente.
	 */
	function getFoto(id, columnaFoto) {
		if(id && columnaFoto) {
			$(columnaFoto).load(WEBROOT + "articulos/get_foto/" + id);
		}
	}

	/**
	 * quitarArticulo() elimina la fila en la que se encuentra el botón
	 */
	function quitarArticulo(elemento) {
		// se elimina la fila de la tabla
		console.log($(elemento).parent().parent().remove().fadeOut(1000));

		// se resta 1(uno) a la cantidad de artículos del pedido.
		$('#cantidad_articulos').html(parseInt($('#cantidad_articulos').html()) - 1);
	}

});
