/**
 * @author ale
 */
$(document).ready(function() {
	$('#nombre').val($('#cliente option:selected').text());
	setDireccion($('#cliente option:selected').val());
	setLocalidad($('#cliente option:selected').val());
	setProvincia($('#cliente option:selected').val());
	$('#cliente').change(function() {
		$('#nombre').val($('#cliente option:selected').text());
		setDireccion($('#cliente option:selected').val());
		setLocalidad($('#cliente option:selected').val());
		setProvincia($('#cliente option:selected').val());
	});

	$('#formulario').submit(function() {
		if(!$('#bultos').val()) {
			alert('Verifique la cantidad de bultos.');
			return false;
		}
		return true;
	});
	/**
	 * 	setDireccion setea la dirección del cliente.
	 */
	function setDireccion(id) {
		if(id) {
			$('#direccionIMG').toggleClass('IMG');
			$.get(WEBROOT + "clientes/get_direccion/" + id, function(data) {
				$('#direccion').val(data);
				$('#direccionIMG').toggleClass('IMG');
			});
		}
	}

	/**
	 * 	setLocalidad setea la Localidad del cliente.
	 */
	function setLocalidad(id) {
		if(id) {
			$('#localidadIMG').toggleClass('IMG');
			$.get(WEBROOT + "clientes/get_localidad/" + id, function(data) {
				$('#localidad').val(data);
				$('#localidadIMG').toggleClass('IMG');
			});
		}
	}

	/**
	 * 	setProvincia setea la provincia del cliente.
	 */
	function setProvincia(id) {
		if(id) {
			$('#provinciaIMG').toggleClass('IMG');
			$.get(WEBROOT + "clientes/get_provincia/" + id, function(data) {
				$('#provincia').val(data);
				$('#provinciaIMG').toggleClass('IMG');
			});
		}
	}

});
