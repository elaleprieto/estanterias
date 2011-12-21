<?php
class OrdenesController extends AppController {
	var $name = 'Ordenes';
	var $components = array('RequestHandler');
	var $helpers = array(
			'Paginator',
			'Form',
			'Time',
			'Ajax',
			'Foto'
	);
	var $paginate = array('Orden' => array(
			//'limit' => 25,
			'recursive' => 3,
			// 'order' => array('Pasillo.distancia' => 'asc', )
		), );

	function index() {
		$this -> Orden -> recursive = 0;
		$this -> set('ordenes', $this -> paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid orden', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> set('orden', $this -> Orden -> read(null, $id));
	}

	function add() {
		if (!empty($this -> data)) {
			$this -> Orden -> create();
			if ($this -> Orden -> save($this -> data)) {
				$this -> Session -> setFlash(__('The orden has been saved', true));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The orden could not be saved. Please, try again.', true));
			}
		}
		$clientes = $this -> Orden -> Pedido -> Cliente -> find('list');
		$articulos = $this -> Orden -> Articulo -> find('list');
		$pedidos = $this -> Orden -> Pedido -> find('list');
		$this -> set(compact('clientes', 'articulos', 'pedidos'));
	}

	function edit($id = null) {
		if (!$id && empty($this -> data)) {
			$this -> Session -> setFlash(__('Invalid orden', true));
			$this -> redirect(array('action' => 'index'));
		}
		if (!empty($this -> data)) {
			if ($this -> Orden -> save($this -> data)) {
				$this -> Session -> setFlash(__('The orden has been saved', true));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The orden could not be saved. Please, try again.', true));
			}
		}
		if (empty($this -> data)) {
			$this -> data = $this -> Orden -> read(null, $id);
		}
		$articulos = $this -> Orden -> Articulo -> find('list', array('order' => array('Articulo.detalle' => 'asc', )));
		$pedidos = $this -> Orden -> Pedido -> find('list');
		$this -> set(compact('articulos', 'pedidos'));
	}

	function delete($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid id for orden', true));
			$this -> redirect(array('action' => 'index'));
		}
		if ($this -> Orden -> delete($id)) {
			$this -> Session -> setFlash(__('Orden deleted', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash(__('Orden was not deleted', true));
		$this -> redirect(array('action' => 'index'));
	}

	function preparar($pedido_id = null) {
		if (isset($this -> params['form']['guardar'])) {
			# si se presionó el botón "Guardar Pedido",
			# se actualizan las Ordenes pero no el estado del Pedido.
			if (!$pedido_id && empty($this -> data)) {
				$this -> Session -> setFlash('Pedido Inválido');
				$this -> redirect(array(
						'controller' => 'pedidos',
						'action' => 'index'
				));
			}
			if (!empty($this -> data)) {
				# Se recorre cada Orden del Pedido y se lo salva.
				# Esto es igual que el Finalizar Pedido.
				foreach ($this->data['Orden'] as $index => $orden) {
					if ($this -> Orden -> save($orden)) {
						$this -> Session -> setFlash('El Pedido ha sido Guardado');
					} else {
						$this -> Session -> setFlash('Se ha producido un error. Por favor, avise al Administrador.');
					}
				}
				$this -> redirect(array(
						'controller' => 'pedidos',
						'action' => 'index'
				));
			}
		} elseif (isset($this -> params['form']['finalizar'])) {
			# si se presionó el botón Finalizar Pedido...
			if (!$pedido_id && empty($this -> data)) {
				$this -> Session -> setFlash('Pedido Inválido');
				$this -> redirect(array(
						'controller' => 'pedidos',
						'action' => 'index'
				));
			}
			if (!empty($this -> data)) {
				# Se agrega la fecha de finalización
				$fecha = new DateTime();
				$this -> data['Pedido']['finalizado'] = $fecha -> format('Y-m-d H:i:s');

				# Esta es la diferencia entre el Guardar y Finalizar: se cambia el Estado del Pedido
				# cuando se lo salva acá, porque viene un campo oculto desde la vista
				# en el que se pone el Estado del Pedido a TRUE
				$this -> Orden -> Pedido -> save($this -> data['Pedido']);

				# Luego se recorre cada Orden del Pedido y se lo salva.
				# Esto es igual que el Guardar Pedido.
				# Además, se resta la cantidad de la Orden del Stock del Artículo
				foreach ($this->data['Orden'] as $index => $orden) {
					$this -> Orden -> create($orden);
					if ($this -> Orden -> save($orden)) {
						$articulo_id = $this -> Orden -> read('articulo_id', $orden['id']);
						$this -> Orden -> Articulo -> recursive = 0;
						$stock = $this -> Orden -> Articulo -> read('stock', $articulo_id['Orden']['articulo_id']);
						$this -> Orden -> Articulo -> id = $articulo_id;
						if ($this -> Orden -> Articulo -> saveField('stock', $stock['Articulo']['stock'] - $orden['cantidad'])) {
							$this -> Session -> setFlash('El Pedido ha sido Finalizado');
						}
					} else {
						$this -> Session -> setFlash('Se ha producido un error. Por favor, avise al Administrador.');
					}
				}
				$this -> redirect(array(
						'controller' => 'pedidos',
						'action' => 'index'
				));
			}
		} elseif (isset($this -> params['form']['salir'])) {
			# si se presionó el botón Cancelar...
			$this -> redirect(array(
					'controller' => 'pedidos',
					'action' => 'index'
			));
		}
		$this -> set('pedido', $this -> Orden -> Pedido -> read(null, $pedido_id));
		$consulta = "SELECT orden_id, cantidad, orden_estado, sin_cargo, id, detalle, unidad, foto, 
					array_agg(pasillo_nombre) AS pasillo_nombre, array_agg(pasillo_lado) AS pasillo_lado, 
					min(pasillo_distancia) AS pasillo_distancia, array_agg(ubicacion_altura) AS ubicacion_altura, 
					array_agg(ubicacion_posicion) AS ubicacion_posicion, array_agg(ubicacion_estado) AS ubicacion_estado 
				FROM (SELECT O.id AS orden_id, O.cantidad AS cantidad, O.estado AS orden_estado, O.sin_cargo AS sin_cargo,
						A.id AS id, A.detalle AS detalle, A.unidad AS unidad, A.foto AS foto, 
						P.nombre AS pasillo_nombre, P.lado AS pasillo_lado, 
						P.distancia AS pasillo_distancia, Ub.altura AS ubicacion_altura, 
						Ub.posicion AS ubicacion_posicion, U.estado AS ubicacion_estado 
					FROM Ordenes AS O, Articulos AS A LEFT JOIN Ubicados AS U ON U.articulo_id = A.id 
						LEFT JOIN Pasillos AS P ON U.pasillo_id = P.id LEFT JOIN Ubicaciones AS Ub ON U.ubicacion_id = Ub.id
					WHERE O.pedido_id	= $pedido_id
					AND O.articulo_id 	= A.id
					ORDER BY ubicacion_estado DESC
					) AS E
				GROUP BY orden_id, cantidad, orden_estado, sin_cargo, id, detalle, unidad, foto
				ORDER BY pasillo_distancia ASC, pasillo_nombre ASC, ubicacion_posicion ASC, ubicacion_altura ASC";
		$articulos = $this -> Orden -> query($consulta);
		$this -> set(compact('articulos'));
	}

	function finalizadas($pedido_id = null) {
		/*$this -> layout = 'mobile';*/
		if (isset($this -> params['form']['finalizar'])) {
			# si se presionó el botón Finalizar Pedido...
			if (!$pedido_id && empty($this -> data)) {
				$this -> Session -> setFlash('Pedido Inválido');
				$this -> redirect(array(
						'controller' => 'pedidos',
						'action' => 'index'
				));
			}
			if (!empty($this -> data)) {
				debug($this -> data);
				$this -> Orden -> Pedido -> save($this -> data['Pedido']);
				foreach ($this->data['Orden'] as $index => $orden) {
					if ($this -> Orden -> save($orden)) {
						$this -> Session -> setFlash('El Pedido ha sido Finalizado');
					} else {
						$this -> Session -> setFlash('Se ha producido un error. Por favor, avise al Administrador.');
					}
				}
				$this -> redirect(array(
						'controller' => 'pedidos',
						'action' => 'index'
				));
			}
		} elseif (isset($this -> params['form']['cancelar'])) {
			# si se presionó el botón Cancelar...
			$this -> redirect(array(
					'controller' => 'pedidos',
					'action' => 'index'
			));
		}
		$this -> set('pedido', $this -> Orden -> Pedido -> read(null, $pedido_id));
		// $this -> set('articulos', $this -> paginate('Orden', array('Orden.pedido_id' => $pedido_id)));
		// $articulos_id = $this -> Orden -> find('list', array('fields' => 'Orden.articulo_id', 'conditions' => array('Orden.pedido_id' => $pedido_id)));
		// $articulos = $this -> Orden -> Articulo -> Ubicado -> find('all', array('conditions' => array('Articulo.id' => $articulos_id, ), 'order' => 'Pasillo.distancia ASC', 'recursive' => 2,));

		/*$consulta = "SELECT O.id AS orden_id, O.cantidad, O.estado AS orden_estado, A.id, A.detalle, A.unidad, A.foto, P.nombre, P.lado, P.distancia, Ub.altura, Ub.posicion, U.estado AS ubicacion_estado
		 FROM Ordenes AS O, Articulos AS A, Ubicados AS U, Pasillos AS P, Ubicaciones AS Ub
		 WHERE O.pedido_id	= $pedido_id
		 AND O.articulo_id 	= A.id
		 AND U.articulo_id 	= A.id
		 AND U.pasillo_id	= P.id
		 AND U.ubicacion_id	= Ub.id
		 ORDER BY P.distancia
		 ;";
		 *
		 *
		 */

		$consulta = "SELECT O.id AS orden_id, O.cantidad, O.estado AS orden_estado, 
					A.id, A.detalle, A.unidad, A.foto
				FROM Ordenes AS O LEFT JOIN Articulos AS A ON O.articulo_id = A.id 
				WHERE O.pedido_id	= $pedido_id
				ORDER BY O.estado DESC, A.detalle ASC
				;";

		$articulos = $this -> Orden -> query($consulta);

		$this -> set(compact('articulos'));
	}

	function agregar($articulo_id) {
		$this -> layout = 'ajax';
		if (!empty($articulo_id)) {
			debug($articulo_id);
			$articulo = $this -> Orden -> Articulo -> findById($articulo_id);
			$this -> set(compact('articulo'));
			$this -> render("/elements/ordenes_pedido");
		}
	}

	/**
	 * actualizar_estado($orden_id):
	 * actualiza el estado a la orden con el valor pasado como parámetro.
	 */
	public function actualizar_estado($orden_id = null, $estado = null) {
		$this -> layout = 'ajax';
		if ($orden_id && $estado) {
			// $estado = $this -> Orden -> field('estado', "id = $orden_id");
			$this -> Orden -> id = $orden_id;
			$this -> Orden -> saveField('estado', $estado, $validate = false);
		}
	}

	/**
	 * set_cantidad($orden_id, $cantidad):
	 * actualiza la cantidad asignada a la orden con el valor pasado como parámetro.
	 */
	public function set_cantidad($orden_id = null, $cantidad = null) {
		$this -> layout = 'ajax';
		if ($orden_id && $cantidad) {
			$this -> Orden -> id = $orden_id;
			$this -> Orden -> saveField('cantidad', $cantidad, $validate = false);
		}
	}

	public function actualizar_codigos() {
		$ordenes = $this -> Orden -> find('all');

		foreach ($ordenes as $orden) {
			$articulo = $this -> Orden -> Articulo -> findById($orden['Orden']['articulo_id']);
			$this -> Orden -> id = $orden['Orden']['id'];
			$this -> Orden -> saveField('articulo_id', $articulo['Articulo']['codigo']);

		}
	}

}
?>