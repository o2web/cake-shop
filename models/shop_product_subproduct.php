<?php
class ShopProductSubproduct extends ShopAppModel {
	var $name = 'ShopProductSubproduct';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ShopProduct' => array(
			'className' => 'Shop.ShopProduct',
			'foreignKey' => 'shop_product_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ParentSubproduct' => array(
			'className' => 'Shop.ShopSubproduct',
			'foreignKey' => 'parent_subproduct_id',
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
		)
	);
}
?>