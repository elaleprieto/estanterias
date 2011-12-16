<?php
class Pedido extends AppModel {
	var $name = 'Pedido';
	var $validate = array(
		//'numero' => array(
			//'numeric' => array(
				//'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
		//	),
		//),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		),
		'Transporte' => array(
			'className' => 'Transporte',
			'foreignKey' => 'transporte_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
		)
	);
	
	
	var $hasMany = array(
		'Orden' => array(
			'className' => 'Orden',
			'foreignKey' => 'pedido_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	

}
?>