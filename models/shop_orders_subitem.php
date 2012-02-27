<?php
class ShopOrdersSubitem extends ShopAppModel {
	var $name = 'ShopOrdersSubitem';
	var $actsAs = array('Tree');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ShopOrdersItem' => array(
			'className' => 'Shop.ShopOrdersItem',
			'foreignKey' => 'shop_orders_item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ShopSubproduct' => array(
			'className' => 'Shop.ShopSubproduct',
			'foreignKey' => 'shop_subproduct_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ParentShopOrdersSubitem' => array(
			'className' => 'Shop.ShopOrdersSubitem',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'ChildShopOrdersSubitem' => array(
			'className' => 'Shop.ShopOrdersSubitem',
			'foreignKey' => 'parent_id',
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
			'rule' => array('comparison', '>', 0),
			'message' => 'Quantity must be positive.'
		)
	);

}
?>