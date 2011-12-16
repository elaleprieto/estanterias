<?php
class TransportesController extends AppController {

	var $name = 'Transportes';

	function index() {
		$this->Transporte->recursive = 0;
		$this->set('transportes', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid transporte', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('transporte', $this->Transporte->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Transporte->create();
			if ($this->Transporte->save($this->data)) {
				$this->Session->setFlash(__('The transporte has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transporte could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid transporte', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Transporte->save($this->data)) {
				$this->Session->setFlash(__('The transporte has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transporte could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Transporte->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for transporte', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Transporte->delete($id)) {
			$this->Session->setFlash(__('Transporte deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Transporte was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>