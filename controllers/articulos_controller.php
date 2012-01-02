<?php
class ArticulosController extends AppController {
	var $name = 'Articulos';
	var $components = array('RequestHandler');
	var $helpers = array(
			'Paginator',
			'Foto',
			'Ajax'
	);
	var $paginate = array('Articulo' => array(
				'limit' => 25,
				'order' => array('Articulo.orden' => 'asc', )
		));

	function index() {
		$this -> helpers[] = 'Foto';
		$this -> Articulo -> recursive = 0;
		$this -> set('articulos', $this -> paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid articulo', true));
			$this -> redirect(array(
					'controller' => 'ubicados',
					'action' => 'index'
			));
		}
		$this -> set('ubicacion', $this -> Articulo -> Ubicado -> find('first', array('conditions' => array(
					'Ubicado.articulo_id' => $id,
					'Ubicado.estado' => false
			))));
		$this -> set('articulo', $this -> Articulo -> read(null, $id));
	}

	function admin_add() {
		if (!empty($this -> data)) {
			# se setea el id para el artículo
			$this -> data['Articulo']['id'] = $this -> data['Articulo']['codigo'];

			# a partir de la posición elegida,
			# se suma uno al orden de los artículos que le siguen.
			$orden = $this -> Articulo -> findById($this -> data['Articulo']['id_posicion']);
			$posicion = $orden['Articulo']['orden'];
			$articulos = $this -> Articulo -> find('list', array(
					'conditions' => array('Articulo.orden >' => $posicion),
					'fields' => array('orden')
			));
			debug($articulos);

			foreach ($articulos as $id => $orden) {
				$this -> Articulo -> id = $id;
				# saveField(string $nombreCampo, string $valorCampo, $validar = false)
				$this -> Articulo -> saveField('orden', ++$orden);
			}

			$this -> data['Articulo']['orden'] = ++$posicion;

			# se guarda el artículo
			$this -> Articulo -> create();
			if ($this -> Articulo -> save($this -> data)) {
				$this -> Session -> setFlash(__('The articulo has been saved', true));
				// $this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The articulo could not be saved. Please, try again.', true));
			}
		}
		$this -> set('articulos', $this -> Articulo -> find('list', array(
				'fields' => 'detalle',
				'order' => 'orden'
		)));
	}

	function edit($id = null) {
		if (!$id && empty($this -> data)) {
			$this -> Session -> setFlash(__('Invalid articulo', true));
			$this -> redirect(array('action' => 'index'));
		}
		if (!empty($this -> data)) {
			if ($this -> Articulo -> save($this -> data)) {
				$this -> Session -> setFlash(__('The articulo has been saved', true));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The articulo could not be saved. Please, try again.', true));
			}
		}
		if (empty($this -> data)) {
			$this -> data = $this -> Articulo -> read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid id for articulo', true));
			$this -> redirect(array('action' => 'index'));
		}
		if ($this -> Articulo -> delete($id)) {
			$this -> Session -> setFlash(__('Articulo deleted', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash(__('Articulo was not deleted', true));
		$this -> redirect(array('action' => 'index'));
	}

	function actualizar() {
		if (!empty($this -> data)) {
			$nombreArchivo = 'articulos.csv';
			$uploaddir = TMP . '/uploads/Articulo/';
			$uploadfile = $uploaddir . $nombreArchivo;

			if (move_uploaded_file($this -> data['Articulo']['archivo']['tmp_name'], $uploadfile)) {
				// $this -> Session -> setFlash("Archivo subido correctamente.");
				// $this -> redirect( array('action' => 'actualizar'));

				if ($this -> Articulo -> actualizar($nombreArchivo)) {
					$this -> Session -> setFlash("Los artículos fueron actualizados.");
				} else {
					$this -> Session -> setFlash(__("No se actualizó ningún artículo porque no se encontraron diferencias.", true));
				}
			} else {
				$this -> Session -> setFlash("Ocurrió un problema subiendo el archivo.");
			}
		}
	}

	function autoComplete() {
		//Partial strings will come from the autocomplete field as
		//$this->data['Post']['subject']
		$this -> set('articulos', Set::combine($this -> Articulo -> find('all', array(
				'conditions' => array('Articulo.detalle LIKE' => $this -> data['Articulo']['detalle'] . '%'),
				'fields' => array(
						'Articulo.codigo',
						'Articulo.detalle'
				)
		), 'Articulo.detalle'), "{n}.Articulo.codigo", "{n}.Articulo.detalle"));
		$this -> layout = 'ajax';
	}

	function admin_listar_desubicados() {
		# Se define la página actual para luego de agregar la ubicación del artículo, volver a esta página.
		if (isset($this -> passedArgs['page'])) {
			$this -> Session -> write('URL.redirect', $this -> passedArgs['page']);
		} else {
			$this -> Session -> write('URL.redirect', '1');
		}

		# busco los articulos que ya están ubicados
		$ubicados = $this -> Articulo -> Ubicado -> find('list', array('fields' => array('Ubicado.articulo_id')));
		# seteo los articulos que no están ubicados
		$this -> set('articulos', $this -> paginate('Articulo', array('NOT' => array('Articulo.id' => $ubicados))));

		# Descomentar para la vista mobile
		// $this -> render('mobile/admin_listar_desubicados', 'mobile');
	}

	function admin_listar($filter = null) {
		# Se define la página actual para luego de agregar la ubicación del artículo, volver a esta página.
		if (isset($this -> passedArgs['0'])) {
			$this -> Session -> write('URL.letra', $this -> passedArgs['0']);
		} else {
			$this -> Session -> write('URL.letra', 'A');
		}
		if (isset($this -> passedArgs['page'])) {
			$this -> Session -> write('URL.page', $this -> passedArgs['page']);
		} else {
			$this -> Session -> write('URL.page', '1');
		}

		$this -> set('filter', $filter);

		// query all distinct first letters used in names
		$consulta = 'SELECT DISTINCT SUBSTRING("detalle", 1, 1) AS detalle 
					FROM (SELECT A.id AS id, detalle
							FROM articulos A
							WHERE A.id NOT IN (SELECT ART.id AS id
												FROM articulos ART, ubicados U
												WHERE ART.id = U.articulo_id)) AS B
					ORDER BY detalle';
		$letras = $this -> Articulo -> query($consulta);

		$links = array();
		// push all letters into a non-nested array
		foreach ($letras as $row) {
			array_push($links, current($row[0]));
		}

		$this -> set('links', $links);

		$this -> paginate['Articulo'] = array(
				'limit' => 30,
				'order' => array('Articulo.orden' => 'asc')
		);

		# busco los articulos que ya están ubicados
		$ubicados = $this -> Articulo -> Ubicado -> find('list', array('fields' => array('Ubicado.articulo_id')));
		# seteo los articulos que no están ubicados
		$data = $this -> paginate('Articulo', array(
				'Articulo.detalle LIKE' => $filter . '%',
				'NOT' => array('Articulo.id' => $ubicados)
		));

		$this -> set('data', $data);
		$this -> set('filter', $filter);

		# Descomentar para la vista mobile
		// $this -> render('mobile/admin_listar', 'mobile');

	}

	function admin_index($filter = null) {
		# Se define la página actual para luego de agregar la ubicación del artículo, volver a esta página.
		if (isset($this -> passedArgs['0'])) {
			$this -> Session -> write('URL.letra', $this -> passedArgs['0']);
		} else {
			$this -> Session -> write('URL.letra', 'A');
		}
		if (isset($this -> passedArgs['page'])) {
			$this -> Session -> write('URL.page', $this -> passedArgs['page']);
		} else {
			$this -> Session -> write('URL.page', '1');
		}

		$this -> set('filter', $filter);

		// query all distinct first letters used in names
		$consulta = 'SELECT DISTINCT SUBSTRING("detalle", 1, 1) AS detalle 
					FROM (SELECT A.id AS id, detalle
							FROM articulos A
							WHERE A.id NOT IN (SELECT ART.id AS id
												FROM articulos ART, ubicados U
												WHERE ART.id = U.articulo_id)) AS B
					ORDER BY detalle';
		$letras = $this -> Articulo -> query($consulta);

		$links = array();
		// push all letters into a non-nested array
		foreach ($letras as $row) {
			array_push($links, current($row[0]));
		}

		$this -> set('links', $links);

		$this -> paginate['Articulo'] = array(
				'limit' => 30,
				'order' => array('Articulo.orden' => 'asc')
		);

		# busco los articulos que ya están ubicados
		$ubicados = $this -> Articulo -> Ubicado -> find('list', array('fields' => array('Ubicado.articulo_id')));
		# seteo los articulos que no están ubicados
		$data = $this -> paginate('Articulo', array('Articulo.detalle LIKE' => $filter . '%'));

		$this -> set('articulos', $data);
		$this -> set('filter', $filter);

		# Descomentar para la vista mobile
		// $this -> render('mobile/admin_listar', 'mobile');

	}

	public function fotografiar($id = null) {
		if (!$id && empty($this -> data)) {
			$this -> Session -> setFlash(__('Artículo inválido', true));
			$this -> redirect(array('action' => 'index'));
		}
		if (!empty($this -> data)) {
			# Obtengo el nombre del archivo
			$nombreArchivo = $this -> data['Articulo']['archivo']['name'];
			# Defino el directorio donde se va a subir la foto
			$uploaddir = IMAGES_URL . 'articulos/';
			# Defino la ruta completa
			$uploadfile = $uploaddir . $nombreArchivo;

			# Verifico la existencia de la foto
			if (!(file_exists($uploadfile . '.jpg') || file_exists($uploadfile . '.png'))) {
				# Si no existe en el directorio, la copio dentro del directorio
				if (!move_uploaded_file($this -> data['Articulo']['archivo']['tmp_name'], $uploadfile)) {
					# Si hubo algún error subiendo el archivo, se le informa al usuario
					$this -> Session -> setFlash("Hubo un problema subiendo la foto.");
					$this -> redirect(array('action' => 'fotografiar'));
				}
			}

			# Una vez que la foto ya se encuentra en el directorio,
			# se procede a actualizar el registro del artículo con la nueva foto.
			# Notar que si la foto ya existe, no se sube nuevamente sino que se utiliza la misma
			# e igualmente se actualiza el artículo con esa foto.
			$this -> data['Articulo']['foto'] = substr($nombreArchivo, 0, strlen($nombreArchivo) - 4);
			if ($this -> Articulo -> save($this -> data)) {
				$this -> Session -> setFlash(__('El artículo ha sido actualizado', true));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('Hubo un problema actualizando el artículo.', true));
			}
		}
		if (empty($this -> data)) {
			$this -> data = $this -> Articulo -> read(null, $id);
		}
	}

	public function get_foto($id = null) {
		$this -> layout = 'ajax';
		if ($id) {
			$this -> set('articulo', $this -> Articulo -> findById($id));
			$this -> render("/elements/get_foto");
		}
	}

	public function get_codigo($id = null) {
		$this -> layout = 'ajax';
		if ($id) {
			$this -> set('articulo', $this -> Articulo -> findById($id));
			$this -> render("/elements/get_codigo");
		}
	}

	public function get_unidad($id = null) {
		$this -> layout = 'ajax';
		if ($id) {
			$this -> set('articulo', $this -> Articulo -> findById($id));
			$this -> render("/elements/get_unidad");
		}
	}
	
	public function get_stock($id = null) {
		$this -> layout = 'ajax';
		if ($id) {
			$this -> set('articulo', $this -> Articulo -> findById($id));
			$this -> render("/elements/get_stock");
		}
	}

	public function get_detalle($id = null) {
		$this -> layout = 'ajax';
		if ($id) {
			$this -> set('articulo', $this -> Articulo -> findById($id));
			$this -> render("/elements/get_detalle");
		}
	}

	/**
	 * Setea el Stock pasado en el formulario
	 */
	public function admin_set_stock($id = null) {
		if (!$id && empty($this -> data)) {
			$this -> Session -> setFlash('Artículo Inválido');
			$this -> redirect(array('action' => 'index'));
		}
		if (!empty($this -> data)) {
			if ($this -> Articulo -> save($this -> data, TRUE, array('stock'))) {
				$this -> Session -> setFlash('El stock ha sido actualizado');
				$this -> redirect(array(
						'controller' => 'articulos',
						'action' => 'index',
						$this -> Session -> read('URL.letra'),
						'page:' . $this -> Session -> read('URL.page')
				));
			} else {
				$this -> Session -> setFlash('Ocurrió un problema. Por favor, inténtelo nuevamente');
			}
		}
		if (empty($this -> data)) {
			$this -> data = $this -> Articulo -> read(null, $id);
		}
	}

	function admin_listar_stock($id = null) {
		$this -> Articulo -> recursive = 0;
		# Opciones de Búsqueda
		$fecha = '2011-12-20';
		$condiciones = array('Articulo.modified >' => $fecha, 'Articulo.stock >=' => 0);
		
		$articulos = $this -> Articulo -> find('all', array('order' => 'Articulo.orden', 'conditions' => $condiciones));
		$this -> set('articulos', $articulos);
		$this -> layout = 'ajax';
	}

	function admin_etiquetas_mini() {
		if (!empty($this -> data)) {
			$this -> layout = 'ajax';
			$this -> render('admin_etiquetas_mini_imprimir');
		}
		$this -> Articulo -> recursive = 0;
		$articulos = $this -> Articulo -> find('list', array('order' => 'Articulo.orden'));
		$this -> set('articulos', $articulos);
	}

}
?>