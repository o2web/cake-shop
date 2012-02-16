<?php 
class ShopFunctComponent extends Object
{
	var $components = array('Acl');
	var $controller = null;
	var $ShopAddress;
	
	function initialize(&$controller) {
		$this->controller =& $controller;
		if(!empty($controller->ShopAddress)){
			$this->ShopAddress = $controller->ShopAddress;
		}
	}
	function init(&$controller) {
		$this->controller =& $controller;
		if(!empty($controller->ShopAddress)){
			$this->ShopAddress = $controller->ShopAddress;
		}
	}
	
	function getAdresses($options=array()){
		$defaultOptions = array(
			'aro'=>null
		);
		$options = array_merge($defaultOptions,$options);
		if(empty($options['aro'])){
			$aroProvider = Configure::read('Shop.address.ACL.aroProvider');
			//debug($aroProvider);
			if(isset($aroProvider['component']) && isset($aroProvider['component'])){
				$aro = $this->ShopFunct->callExternalfunction($aroProvider);
			}
			if(empty($aro)){
				$aro = Configure::read('Shop.address.ACL.defaultAro');
			}
		}else{
			$aro = $options['aro'];
		}
		
		if(!$this->ShopAddress){
			App::import('ShopAddress', 'Shop.ShopAddress');
			$this->ShopAddress = new ShopAddress;
		}
		
		//!!!!!!! Méthode trop lente quand il y a beaucoup d'élémemts. !!!!!!!
		$this->ShopAddress->recursive = -1;
		$allAdresses = $this->ShopAddress->find('all',array('conditions'=>array('active'=>1)));
		if(count($allAdresses)>250){
			trigger_error(__d('shop',"Too many entries for the current analysing method. May be slow.", true), E_USER_NOTICE);
		}
		$adresses = true();
		foreach($allAdresses as $adress){
			if(!$this->Acl->check($aro, array('model'=>$this->ShopAddress->alias,'foreign_key'=>$adress['ShopAddress']['id']))){
				$adresses[] = $adress;
			}
		}
		return $adresses;
	}

}
?>