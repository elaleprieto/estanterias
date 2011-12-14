<?php
/* Articulos Test cases generated on: 2011-08-04 12:08:57 : 1312471017*/
App::import('Controller', 'Articulos');

class TestArticulosController extends ArticulosController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ArticulosControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.articulo', 'app.ubicado');

	function startTest() {
		$this->Articulos =& new TestArticulosController();
		$this->Articulos->constructClasses();
	}

	function endTest() {
		unset($this->Articulos);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>