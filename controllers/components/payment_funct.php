<?php 
class PaymentFunctComponent extends Object
{
	var $components = array('Shop.ComponentLoader','Shop.OrderMaker');
	var $controller = null;
	var $ShopPayment = null;
	
	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	function init(&$controller) {
		$this->controller =& $controller;
	}
	
	function initShopPayment(){
		if(!$this->ShopPayment){
			$this->ShopPayment = ClassRegistry::init(array('class' => 'Shop.ShopPayment', 'alias' => 'ShopPayment'));
		}
		return $this->ShopPayment;
	}
	
	function loadPaimentComponent($name){
		$importName = 'Shop.'.Inflector::classify($name);
		if (App::import('Component', $importName)) {
			return $this->ComponentLoader->loadComponent(Inflector::classify($name).'Payment');
		}
		return null;
	}
	
	function setStatus($payment,$status){
		$this->initShopPayment();
		if(is_numeric($payment)){
			$payment = $this->ShopPayment->read(Null,$payment);
		}
		if(!empty($payment) && $status != $payment['ShopPayment']['status']){
			$data['id'] = $payment['ShopPayment']['id'];
			$data['status'] = $status;
			$this->ShopPayment->save($data);
			if(!empty($payment['ShopOrder'])){
				foreach($payment['ShopOrder'] as $order){
					$this->OrderMaker->refresh($order['id']);
				}
			}else{
				$this->log('Payment is not associated to any Order',LOG_DEBUG);
			}
		}elseif(!empty($payment)){
			$this->log('Payment could not be found',LOG_DEBUG);
		}
	}
	

}


?>