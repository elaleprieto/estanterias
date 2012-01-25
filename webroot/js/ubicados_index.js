$(document).ready(function() {
	$('#formulario').submit(function(){
		$.post(WEBROOT + "ubicados/get_ubicados", $('#formulario').serialize(), function(data) {
		  $('#listado').html(data);
		});
		return false;
	});
});
