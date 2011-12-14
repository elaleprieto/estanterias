<?php
class ClientesController extends AppController {

	var $name = 'Clientes';

	function index() {
		$this -> Cliente -> recursive = 0;
		$this -> set('clientes', $this -> paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid cliente', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> set('cliente', $this -> Cliente -> read(null, $id));
	}

	function add() {
		if (!empty($this -> data)) {
			$this -> Cliente -> create();
			if ($this -> Cliente -> save($this -> data)) {
				$this -> Session -> setFlash(__('The cliente has been saved', true));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The cliente could not be saved. Please, try again.', true));
			}
		}
		$localidades = $this -> Cliente -> Localidad -> find('list');
		$ivas = $this -> Cliente -> Iva -> find('list');
		$this -> set(compact('localidades', 'ivas'));
	}

	function edit($id = null) {
		if (!$id && empty($this -> data)) {
			$this -> Session -> setFlash(__('Invalid cliente', true));
			$this -> redirect(array('action' => 'index'));
		}
		if (!empty($this -> data)) {
			if ($this -> Cliente -> save($this -> data)) {
				$this -> Session -> setFlash(__('The cliente has been saved', true));
				$this -> redirect(array('action' => 'index'));
			} else {
				$this -> Session -> setFlash(__('The cliente could not be saved. Please, try again.', true));
			}
		}
		if (empty($this -> data)) {
			$this -> data = $this -> Cliente -> read(null, $id);
		}
		$localidades = $this -> Cliente -> Localidad -> find('list');
		$ivas = $this -> Cliente -> Iva -> find('list');
		$this -> set(compact('localidades', 'ivas'));
	}

	function delete($id = null) {
		if (!$id) {
			$this -> Session -> setFlash(__('Invalid id for cliente', true));
			$this -> redirect(array('action' => 'index'));
		}
		if ($this -> Cliente -> delete($id)) {
			$this -> Session -> setFlash(__('Cliente deleted', true));
			$this -> redirect(array('action' => 'index'));
		}
		$this -> Session -> setFlash(__('Cliente was not deleted', true));
		$this -> redirect(array('action' => 'index'));
	}

	public function actualizar() {
		if (!empty($this -> data)) {
			$nombreArchivo = 'clientes.csv';
			$uploaddir = TMP . '/uploads/cliente/';
			$uploadfile = $uploaddir . $nombreArchivo;

			if (move_uploaded_file($this -> data['Cliente']['archivo']['tmp_name'], $uploadfile)) {
				# se llama al modelo
				if ($this -> Cliente -> actualizar($nombreArchivo)) {
					$this -> Session -> setFlash("Clientes actualizados.");
				} else {
					$this -> Session -> setFlash(__("No se actualizó ningun cliente porque no se encontraron diferencias.", true));
				}
			} else {
				$this -> Session -> setFlash("Ocurrió un problema subiendo el archivo.");
			}
		}
	}

}
?>