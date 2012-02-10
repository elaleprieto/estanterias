<?php
class PedidosController extends AppController {
	var $name = 'Pedidos';
	var $components = array('RequestHandler');
	var $helpers = array(
		'Ajax',
		'Paginator',
		'Time',
		'Foto',
		'Javascript',
		'Js' => array('Jquery')
	);

	function index() {
		$this -> Pedido -> recursive = 1;
		$this -> set('pedidos', $this -> paginate('Pedido', array('Pedido.estado' => '0')));
	}

	function view($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid pedido', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> set('pedido', $this -> Pedido -> read(null, $id));
	}

	function admin_index($pedido_id = null) {
		if ($pedido_id) {
			$this -> Pedido -> id = $pedido_id;
			$this -> Pedido -> saveField('estado', 0);
		}
		$this -> Pedido -> recursive = 1;
		$this -> set('pedidos', $this -> paginate('Pedido', array('Pedido.estado' => '0')));
	}

	function admin_finalizados() {
		$this -> Pedido -> recursive = 1;
		$this -> paginate = array('Pedido' => array('order' => array('finalizado' => 'DESC'), ));
		$this -> set('pedidos', $this -> paginate('Pedido', array('Pedido.estado' => '1')));
	}

	function admin_controlados($pedido_id = null) {
		if ($pedido_id) {
			# Se verifica que el estado del pedido sea "Finalizado", es decir, estado == 1
			# Si no, no se cambia el estado del Pedido.
			# Además, se verifica que la petición provenga de la acción Finalizados del controller Pedidos.
			# Esto debería evitar errores.
			$estado = $this -> Pedido -> read('estado', $pedido_id);
			if ($estado['Pedido']['estado'] == 1 && strpos($this -> referer(), 'pedidos') && strpos($this -> referer(), 'finalizados')) {
				# Se calcula el tiempo de Control en segundos.
				$fecha = new DateTime();
				$finalizado = $this -> Pedido -> read('finalizado', $pedido_id);
				$finalizado = new DateTime($finalizado['Pedido']['finalizado']);
				$intervalo = $fecha -> diff($finalizado);
				$tiempo_control = $this -> Pedido -> read('tiempo_control', $pedido_id);

				# Se guardan los datos del Pedido
				$this -> Pedido -> id = $pedido_id;
				$this -> Pedido -> saveField('estado', 2);
				$this -> Pedido -> saveField('controlado', $fecha -> format('Y-m-d H:i:s'));
				$this -> Pedido -> saveField('tiempo_control', $tiempo_control['Pedido']['tiempo_control'] + $intervalo -> format('%d') * 24 * 3600 + $intervalo -> format('%h') * 3600 + $intervalo -> format('%i') * 60 + $intervalo -> format('%s'));

				# Se actualiza el Stock
				$ordenes = $this -> Pedido -> Orden -> find('list', array(
					'conditions' => array(
						'Orden.pedido_id' => $pedido_id,
						'Orden.estado' => TRUE
					),
					'fields' => array(
						'Orden.articulo_id',
						'Orden.cantidad',
					)
				));
				foreach ($ordenes as $articulo_id => $cantidad) {
					$this -> loadModel('Articulo');
					$this -> Articulo -> recursive = 0;
					$stock = $this -> Articulo -> read('stock', $articulo_id);
					$this -> Articulo -> id = $articulo_id;
					$this -> Articulo -> saveField('stock', $stock['Articulo']['stock'] - $cantidad);
				}

				$this -> admin_correo_faltantes($pedido_id);
			}
		}
		$this -> Pedido -> recursive = 1;
		$this -> paginate = array('Pedido' => array('order' => array('finalizado' => 'DESC'), ));
		$this -> set('pedidos', $this -> paginate('Pedido', array('Pedido.estado' => '2')));
	}

	function admin_add() {
		if (!empty($this -> data) && isset($this -> data['Orden'])) {
			$this -> Pedido -> create();
			if ($this -> Pedido -> save($this -> data)) {
				# actualizo los datos del Cliente
				$this -> Pedido -> Cliente -> id = $this -> data['Pedido']['cliente_id'];
				if (isset($this -> data['Pedido']['transporte_id'])) {
					$this -> Pedido -> Cliente -> saveField('transporte_id', $this -> data['Pedido']['transporte_id']);
				} else {
					$this -> Pedido -> Cliente -> saveField('transporte_id', 0);
				}
				if (isset($this -> data['Pedido']['contrarrembolso'])) {
					$this -> Pedido -> Cliente -> saveField('contrarrembolso', $this -> data['Pedido']['contrarrembolso']);
				} else {
					$this -> Pedido -> Cliente -> saveField('contrarrembolso', FALSE);
				}
				if (isset($this -> data['Pedido']['cobinpro'])) {
					$this -> Pedido -> Cliente -> saveField('cobinpro', $this -> data['Pedido']['cobinpro']);
				} else {
					$this -> Pedido -> Cliente -> saveField('cobinpro', FALSE);
				}
				if (isset($this -> data['Pedido']['b'])) {
					$this -> Pedido -> Cliente -> saveField('presupuesto', $this -> data['Pedido']['b']);
				} else {
					$this -> Pedido -> Cliente -> saveField('presupuesto', 0);
				}
				# inserto las ordenes
				foreach ($this -> data['Orden'] as $orden) {
					$this -> Pedido -> Orden -> create();
					$this -> Pedido -> Orden -> set(array(
						'articulo_id' => $orden['id'],
						'cantidad' => $orden['Cantidad'],
						'cantidad_original' => $orden['Cantidad'],
						'sin_cargo' => $orden['SinCargo'],
						'observaciones' => $orden['Observaciones'],
						'pedido_id' => $this -> Pedido -> id,
					));
					$this -> Pedido -> Orden -> save();
				}
				$this -> Session -> setFlash('El pedido ha sido creado');
				$this -> redirect(array('action' => 'index', ));
			} else {
				$this -> Session -> setFlash('El pedido no se ha guardado, intente nuevamente.');
			}
		}
		$condicionesArticulo = array(
			'OR' => array('NOT' => array('OR' => array(
						array("Articulo.detalle LIKE" => "FAROL%"),
						array("Articulo.detalle LIKE" => "BULONES%")
					))),
			array("Articulo.precio >" => "0")
		);
		$articulos = $this -> Pedido -> Orden -> Articulo -> find('list', array(
			'conditions' => $condicionesArticulo,
			'order' => array('Articulo.orden')
		));
		$clientes = $this -> Pedido -> Cliente -> find('list', array('order' => array('Cliente.nombre')));
		$transportes = $this -> Pedido -> Transporte -> find('list', array('order' => array('Transporte.nombre')));
		$this -> set(compact('clientes', 'articulos', 'transportes'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this -> data)) {
			$this -> Session -> setFlash(__('Invalid pedido', true));
			$this -> redirect(array('action' => 'index'));
		}
		if (!empty($this -> data)) {
			if (!isset($this -> data['Pedido']['b'])) {$this -> data['Pedido']['b'] = 0;
			}
			if (!isset($this -> data['Pedido']['cobinpro'])) {$this -> data['Pedido']['cobinpro'] = FALSE;
			}
			if (!isset($this -> data['Pedido']['contrarrembolso'])) {$this -> data['Pedido']['contrarrembolso'] = FALSE;
			}
			if ($this -> Pedido -> save($this -> data)) {

				# Me traigo todas las ordenes que tienen el id del pedido que se está modificando
				$ordenes = $this -> Pedido -> Orden -> findAllByPedidoId($id);
				$nuevas = $this -> data['Orden'];

				# Actualizo las que está creadas
				foreach ($ordenes as $orden) {
					$existe = FALSE;
					foreach ($nuevas as $index => $datos) {
						// debug($index);
						# verificación de variables
						if (!isset($datos['estado'])) {$datos['estado'] = FALSE;
						}
						if (!isset($datos['SinCargo'])) {$datos['SinCargo'] = FALSE;
						}
						if (!isset($datos['Observaciones'])) {$datos['Observaciones'] = "";
						}
						if ($orden['Orden']['articulo_id'] == $datos['id'] && $orden['Orden']['sin_cargo'] == $datos['SinCargo']) {
							$this -> Pedido -> Orden -> id = $orden['Orden']['id'];
							$this -> Pedido -> Orden -> saveField('cantidad', $datos['Cantidad']);
							$this -> Pedido -> Orden -> saveField('estado', $datos['estado']);
							$this -> Pedido -> Orden -> saveField('observaciones', $datos['Observaciones']);
							$existe = TRUE;
						}
					}
					# Se eliminan las ordenes que no existan en las ordenes que vienen nuevas.
					if (!$existe)
						$this -> Pedido -> Orden -> delete($orden['Orden']['id']);
				}

				# Se crean las órdenes que no fueron actualizadas porque no existían
				foreach ($nuevas as $index => $datos) {
					# verificación de variables
					if (!isset($datos['estado'])) {$datos['estado'] = FALSE;
					}
					$existe = $this -> Pedido -> Orden -> find('list', array('conditions' => array(
							'Orden.articulo_id' => $datos['id'],
							'Orden.estado' => $datos['estado'],
							'Orden.pedido_id' => $this -> Pedido -> id
						)));

					if (!$existe) {
						$this -> Pedido -> Orden -> create();

						# verificación de variables
						if (!isset($datos['SinCargo'])) {$datos['SinCargo'] = FALSE;
						}
						if (!isset($datos['Observaciones'])) {$datos['Observaciones'] = "";
						}

						$this -> Pedido -> Orden -> set(array(
							'articulo_id' => $datos['id'],
							'cantidad' => $datos['Cantidad'],
							'cantidad_original' => $datos['Cantidad'],
							'estado' => $datos['estado'],
							'sin_cargo' => $datos['SinCargo'],
							'observaciones' => $datos['Observaciones'],
							'pedido_id' => $this -> Pedido -> id,
						));
						$this -> Pedido -> Orden -> save();
					}
				}
				# Se vuelva a la página anterior
				$this -> redirect($this -> Session -> read('URL.redirect'));
			} else {
				$this -> Session -> setFlash(__('The pedido could not be saved. Please, try again.', true));
			}
		}
		$this -> data = $this -> Pedido -> read(null, $id);
		$condicionesArticulo = array(
			'OR' => array('NOT' => array('OR' => array(
						array("Articulo.detalle LIKE" => "FAROL%"),
						array("Articulo.detalle LIKE" => "BULONES%")
					))),
			array("Articulo.precio >" => "0")
		);
		$articulos = $this -> Pedido -> Orden -> Articulo -> find('list', array(
			'conditions' => $condicionesArticulo,
			'order' => array('Articulo.orden')
		));
		$clientes = $this -> Pedido -> Cliente -> find('list');
		$ordenes = $this -> Pedido -> Orden -> find('all', array(
			'conditions' => array('pedido_id' => $id),
			'order' => array('Articulo.orden')
		));
		$transportes = $this -> Pedido -> Transporte -> find('list', array('order' => array('Transporte.nombre')));
		$this -> set(compact('clientes', 'articulos', 'ordenes', 'transportes'));
		
		# Se guarda la página desde donde se viene para después de editar el Pedido, retornar a ella.
		$this -> Session -> write('URL.redirect', $this -> referer());
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this -> Session -> setFlash('Pedido inválido');
			$this -> redirect(array('action' => 'index'));
		}
		if ($this -> Pedido -> delete($id)) {
			# Lo que se hace acá es eliminar todas las ordenes que tenía el pedido

			# Me traigo todas las ordenes que tienen el id del pedido que se está eliminando
			$ordenes = $this -> Pedido -> Orden -> findAllByPedidoId($id);
			# Elimino todas las órdenes que busqué
			foreach ($ordenes as $orden) {
				$this -> Pedido -> Orden -> delete($orden['Orden']['id']);
			}

			$this -> Session -> setFlash('El pedido fue eliminado');
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash('Ocurrió un error. El pedido no fue eliminado.');
		$this -> redirect(array('action' => 'index'));
	}

	function autocomplete() {
		# La cadena buscada en el campo autocomplete va a venir en
		# $this -> data['Pedido']['articulo']
		# con Jquery parece que viene en $this->params['url']['q']
		// $articulo = strtoupper($this -> data['Pedido']['articulo']);
		$articulo = strtoupper($this -> params['url']['q']);
		$this -> set('articulos', $this -> Pedido -> Orden -> Articulo -> find('all', array(
			'conditions' => array('Articulo.detalle LIKE' => '%' . $articulo . '%'),
			'fields' => array(
				'detalle',
				'id'
			),
			'order' => 'orden',
		)));
		$this -> layout = 'ajax';
	}

	function admin_imprimir($id = null) {
		if (!$id) {
			$this -> Session -> setFlash('Pedido inválido');
			$this -> redirect(array('action' => 'index'));
		}
		$pedido = $this -> Pedido -> read(null, $id);
		$consulta = "SELECT orden_id, posicion, cantidad, cantidad_original, orden_estado, sin_cargo, id, detalle, unidad, foto, observaciones, 
					array_agg(pasillo_nombre) AS pasillo_nombre, array_agg(pasillo_lado) AS pasillo_lado, 
					min(pasillo_distancia) AS pasillo_distancia, array_agg(ubicacion_altura) AS ubicacion_altura, 
					array_agg(ubicacion_posicion) AS ubicacion_posicion, array_agg(ubicacion_estado) AS ubicacion_estado 
				FROM (SELECT O.id AS orden_id, O.cantidad AS cantidad, O.cantidad_original AS cantidad_original, O.estado AS orden_estado, O.sin_cargo AS sin_cargo, O.observaciones AS observaciones, 
						A.id AS id, A.detalle AS detalle, A.unidad AS unidad, A.foto AS foto,
						A.orden AS posicion,
						P.nombre AS pasillo_nombre, P.lado AS pasillo_lado, 
						P.distancia AS pasillo_distancia, Ub.altura AS ubicacion_altura, 
						Ub.posicion AS ubicacion_posicion, U.estado AS ubicacion_estado 
					FROM Ordenes AS O, Articulos AS A LEFT JOIN Ubicados AS U ON U.articulo_id = A.id 
						LEFT JOIN Pasillos AS P ON U.pasillo_id = P.id LEFT JOIN Ubicaciones AS Ub ON U.ubicacion_id = Ub.id
					WHERE O.pedido_id	= $id
					AND O.articulo_id 	= A.id
					ORDER BY ubicacion_estado DESC
					) AS E
				GROUP BY orden_id, posicion, cantidad, cantidad_original, orden_estado, sin_cargo, id, detalle, unidad, foto, observaciones
				ORDER BY posicion";
		$ordenes = $this -> Pedido -> Orden -> query($consulta);

		$this -> set(compact('pedido', 'ordenes'));
		$this -> layout = 'ajax';
	}

	/**
	 * admin_actualizar: la idea de esta función es levantar el archivo AUXFACTU.DBF
	 * luego, se levantan las ordenes que pertenecen al pedido.
	 * Las ordenes del pedido están en el archivo: FACTURAS.DBF
	 */
	public function admin_actualizar() {
		if (!empty($this -> data)) {
			$aux = 'pedidos_aux.csv';
			$facturas = 'pedidos_facturas.csv';
			$uploadDir = TMP . 'uploads' . DS . 'pedido';
			$uploadAux = $uploadDir . DS . $aux;
			$uploadFacturas = $uploadDir . DS . $facturas;

			if (move_uploaded_file($this -> data['Pedido']['aux']['tmp_name'], $uploadAux) && move_uploaded_file($this -> data['Pedido']['facturas']['tmp_name'], $uploadFacturas)) {

				# se abren los archivos
				$pedidos = fopen($uploadAux, "r") or die('Error al abrir el archivo ' . $uploadAux);
				$ordenes = fopen($uploadFacturas, "r") or die('Error al abrir el archivo ' . $uploadFacturas);

				# se lee la 1° columna como encabezados
				$headerPedidos = fgetcsv($pedidos);
				$headerOrdenes = fgetcsv($ordenes);

				# se lee cada fila del archivo AUXFACTU.DBF y se crean o actualizan los pedidos
				while (($fila = fgetcsv($pedidos, 0, ";")) !== FALSE) {
					$data = array();

					$data['Pedido']['id'] = (integer) trim($fila[1]);
					$fecha = DateTime::createFromFormat('d/m/Y', trim($fila[2]));
					$data['Pedido']['created'] = $fecha -> format('Y-m-d H:i:s');
					$data['Pedido']['cliente_id'] = (integer) trim($fila[3]);
					$data['Pedido']['estado'] = 4;

					if ($data['Pedido']['cliente_id'] >= 1) {
						$this -> Pedido -> create($data);
						$this -> Pedido -> save($data);
					}
				}

				# se lee cada fila del archivo FACTURAS.DBF y se crean o actualizan las ordenes
				while (($fila = fgetcsv($ordenes, 0, ";")) !== FALSE) {
					$data = array();

					$data['Orden']['pedido_id'] = (integer) trim($fila[1]);
					$data['Orden']['articulo_id'] = (integer) trim($fila[2]);
					$data['Orden']['cantidad'] = (float) trim($fila[3]);
					$data['Orden']['estado'] = TRUE;

					if (($data['Orden']['pedido_id'] >= 1) && ($data['Orden']['articulo_id'] >= 1) && ($data['Orden']['cantidad'] > 0)) {
						$this -> Pedido -> Orden -> create($data);
						$this -> Pedido -> Orden -> save($data);
					}
				}

				fclose($pedidos);
				fclose($ordenes);
			} else {
				$this -> Session -> setFlash("Ocurrió un problema subiendo el archivo.");
			}
		}
	}

	/**
	 * admin_correo_faltantes($id): recorre el Pedido pasado como parámetro y arma un correo para informarlo al destinatario.
	 * @param id: el Pedido buscado.
	 */
	public function admin_correo_faltantes($id = null) {
		if (!$id) {
			$this -> Session -> setFlash('Pedido inválido');
			$this -> redirect(array('action' => 'index'));
		}
		App::import('Lib', 'phpMailer', array('file' => 'phpMailer' . DS . 'class.phpmailer.php'));
		App::import('Lib', 'phpMailer', array('file' => 'phpMailer' . DS . 'class.smtp.php'));
		App::import('Lib', 'contras', array('file' => 'contras' . DS . 'pedidos.correo.php'));

		$pedido = $this -> Pedido -> read('cliente_nombre', $id);

		# Se buscan las órdenes que no se enviaron por falta de Stock
		$ordenes_sin_stock = $this -> Pedido -> Orden -> find('all', array(
			'conditions' => array(
				'Orden.pedido_id' => $id,
				'Orden.estado' => FALSE
			),
			'fields' => array(
				'Orden.articulo_id',
				'Orden.articulo_detalle',
				'Orden.cantidad',
				'Orden.cantidad_original',
			),
			'recursive' => 0
		));

		# Se buscan las órdenes que fueron enviadas pero en una Cantidad menor a la pedida (posiblemente por falta de Stock)
		$ordenes_menores = $this -> Pedido -> Orden -> find('all', array(
			'conditions' => array(
				'Orden.pedido_id' => $id,
				'Orden.estado' => TRUE,
				'Orden.cantidad_original > Orden.cantidad'
			),
			'fields' => array(
				'Orden.articulo_id',
				'Orden.articulo_detalle',
				'Orden.cantidad',
				'Orden.cantidad_original',
			),
			'recursive' => 0
		));
		$body = '';
		$estilo_cabeceras = 'style="border-bottom: 2px solid black;"';

		if (sizeof($ordenes_sin_stock) > 0) {
			$body = "<b>Artículos No Enviados por Falta de Stock: </b>";
			$body .= "<br /><br />";
			$body .= '<table>';
			$body .= '<tr>';
			$body .= "<th $estilo_cabeceras>Código</th>";
			$body .= "<th $estilo_cabeceras>Detalle</th>";
			$body .= "<th $estilo_cabeceras>Cantidad Pedida</th>";
			$body .= '</tr>';
			foreach ($ordenes_sin_stock as $orden) {
				$body .= '<tr>';
				$body .= '<td style="text-align: center;">' . $orden['Orden']['articulo_id'] . '</td>';
				$body .= '<td>' . $orden['Orden']['articulo_detalle'] . '</td>';
				$body .= '<td style="text-align: center;">' . $orden['Orden']['cantidad_original'] . '</td>';
				$body .= '</tr>';
			}
			$body .= '</table>';
		}
		if (sizeof($ordenes_menores) > 0) {
			if (sizeof($ordenes_sin_stock) > 0) {
				# Si ya se escribió en el correo, se traza un línea
				$body .= '<br /><hr /><br />';
			}
			$body .= "<b>Artículos Enviados pero en una Cantidad menor a la pedida (posiblemente por falta de Stock): </b>";
			$body .= "<br /><br />";
			$body .= '<table>';
			$body .= '<tr>';
			$body .= "<th $estilo_cabeceras>Código</th>";
			$body .= "<th $estilo_cabeceras>Detalle</th>";
			$body .= "<th $estilo_cabeceras>Cantidad Enviada</th>";
			$body .= "<th $estilo_cabeceras>Cantidad Pedida</th>";
			$body .= '</tr>';
			foreach ($ordenes_menores as $orden) {
				$body .= '<tr>';
				$body .= '<td style="text-align: center;">' . $orden['Orden']['articulo_id'] . '</td>';
				$body .= '<td>' . $orden['Orden']['articulo_detalle'] . '</td>';
				$body .= '<td style="text-align: center;">' . $orden['Orden']['cantidad'] . '</td>';
				$body .= '<td style="text-align: center;">' . $orden['Orden']['cantidad_original'] . '</td>';
				$body .= '</tr>';
			}
			$body .= '</table>';
		}

		$mail = new PHPMailer();

		# la dirección del servidor, p. ej.: smtp.servidor.com
		# con SSL habilitado, el puerto 465 y demás opciones para Gmail
		$mail -> Host = "smtp.googlemail.com";
		$mail -> SMTPSecure = "ssl";
		$mail -> Port = '465';
		$mail -> SMTPKeepAlive = true;
		$mail -> Mailer = "smtp";
		$mail -> CharSet = 'utf-8';
		$mail -> IsSMTP();

		# dirección remitente, p. ej.: no-responder@miempresa.com
		$mail -> From = "general@elefe.com.ar";

		# nombre remitente, p. ej.: "Servicio de envío automático"
		$mail -> FromName = "ELEFE - Artículos Faltantes";

		# asunto y cuerpo alternativo del mensaje
		$mail -> Subject = 'Pedido de ' . $pedido['Pedido']['cliente_nombre'];
		$mail -> AltBody = "A continuación se detallan los artículos faltantes:";

		# si el cuerpo del mensaje es HTML
		$mail -> MsgHTML($body);

		# podemos hacer varios AddAdress
		$mail -> AddAddress("aleprieto@elefe.com.ar", "Alejandro Prieto");
		$mail -> AddAddress("aleprieto@gmail.com", "Alejandro Prieto");

		# si el SMTP necesita autenticación
		$mail -> SMTPAuth = true;

		# credenciales usuario
		$mail -> Username = USUARIO_GENERAL;
		$mail -> Password = CONTRASENIA_GENERAL;
		$mail -> Send();

		// if (!$mail -> Send()) {
		// echo "Error enviando: " . $mail -> ErrorInfo;
		// } else {
		// echo "¡¡Enviado!!";
		// }
	}

}
?>