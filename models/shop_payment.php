<?php
class ShopPayment extends ShopAppModel {
	var $name = 'ShopPayment';
	var $displayField = 'id';
	
	var $actsAs = array('Acl' => array('type' => 'controlled'));
	
	var $status = array('pending','approved','received','error','void');
	var $okStatus = array('approved','received');
	
	var $types = array(
			'agreement'=>array(),
			'money'=>array(),
			'check'=>array(),
			'credit card'=>array(),
			'debit card'=>array(),
			'paypal'=>array(),
			'wire transfer'=>array()
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'ShopOrdersPayment' => array(
			'className' => 'Shop.ShopOrdersPayment',
			'foreignKey' => 'payment_id',
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

	var $hasAndBelongsToMany = array(
		'ShopOrder' => array(
			'className' => 'Shop.ShopOrder',
			'joinTable' => 'shop_orders_payments',
			'foreignKey' => 'payment_id',
			'associationForeignKey' => 'order_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

	var $rootNodeAlias = "shopPayment";

	function parentNode() {
		$ref = $this->rootNodeAlias;
		$parent = $this->node($ref);
		//debug($parent);
		if(empty($parent)){
			//debug($rootNode);
			$data["alias"] = $this->rootNodeAlias;
			$this->Aco->create();
			$this->Aco->save($data);
		}
		//debug($ref);
		return $ref;
	}
}
?>