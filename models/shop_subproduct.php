<?php
class ShopSubproduct extends ShopAppModel {
	var $name = 'ShopSubproduct';
	var $displayField = 'label';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'ShopProductSubproduct' => array(
			'className' => 'ShopProductSubproduct',
			'foreignKey' => 'shop_subproduct_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ChildSubproduct' => array(
			'className' => 'ShopProductSubproduct',
			'foreignKey' => 'parent_subproduct_id',
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