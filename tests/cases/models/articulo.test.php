<?php
/* Articulo Test cases generated on: 2011-08-04 12:08:53 : 1312471013*/
App::import('Model', 'Articulo');

class ArticuloTestCase extends CakeTestCase {
	var $fixtures = array('app.articulo', 'app.ubicado');

	function startTest() {
		$this->Articulo =& ClassRegistry::init('Articulo');
	}

	function endTest() {
		unset($this->Articulo);
		ClassRegistry::flush();
	}

}
?>