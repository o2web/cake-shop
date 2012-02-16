<?php 
class ProductMakerComponent extends Object{
	
	var $ShopProduct = null;
	var $controller = null;
	
	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	function init(&$controller) {
		$this->controller =& $controller;
	}
	
	function initShopProduct(){
		if(!$this->ShopProduct){
			if(!empty($controller) && !empty($controller->ShopProduct)){
				$this->ShopProduct = $controller->ShopProduct;
			}else{
				$this->ShopProduct = ClassRegistry::init(array('class' => 'Shop.ShopProduct', 'alias' => 'ShopProduct'));
			}
		}
		return $this->ShopProduct;
	}
	
	function add($options){
		$defaultShippingRequired = Configure::read('Shop.defaultShippingRequired');
		$defaultOptions = array(
			'code' => null,
			'model' => null,
			'foreign_id' => null,
			'price' => null,
			'shipping_req' => $defaultShippingRequired,
			'needed_data' => null,
			'tax_applied' => null,
			'active' => 1
		);
		if(isset($options['model'])){
			$this->RelatedModel =& ClassRegistry::init($options['model']);
			if(isset($this->RelatedModel->productOptions)){
				$defaultOptions = array_merge($defaultOptions,$this->RelatedModel->productOptions);
			}
		}
		$options = array_merge($defaultOptions,$options);
		$this->initShopProduct();
		if($this->ShopProduct->save($options)){
			return $this->ShopProduct->id;
		}
		return false;
	}
	
} 
?>