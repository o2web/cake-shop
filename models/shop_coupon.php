<?php
class ShopCoupon extends ShopAppModel {
	var $name = 'ShopCoupon';
	var $displayField = 'code';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ShopPromotion' => array(
			'className' => 'Shop.ShopPromotion',
			'foreignKey' => 'shop_promotion_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ShopOrder' => array(
			'className' => 'Shop.ShopOrder',
			'foreignKey' => 'shop_order_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>