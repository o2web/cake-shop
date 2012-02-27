<?php
class ShopOrdersItem extends ShopAppModel {
	var $name = 'ShopOrdersItem';
	var $displayField = 'id';
	
	var $actsAs = array('Shop.Serialized'=>array('data','item_tax_applied'));
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ShopProduct' => array(
			'className' => 'Shop.ShopProduct',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ShopOrder' => array(
			'className' => 'Shop.ShopOrder',
			'foreignKey' => 'order_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasMany = array(
		'ShopOrdersSubitem' => array(
			'className' => 'Shop.ShopOrdersSubitem',
			'foreignKey' => 'shop_orders_item_id',
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
	
	var $validate = array(
		'nb' => array(
			'rule' => array('comparison', '>', 1),
			'message' => 'Quantity must be positive.'
		)
	);
}
?>