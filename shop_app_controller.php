<?php
class ShopAppController extends AppController {
	var $pluginVersion = "0.1.5.0";
	
	function __construct() {
		//////// config ////////
		App::import('Lib', 'Shop.ShopConfig');
		$conf = ShopConfig::load();
		if(!empty($conf['plugComponent'])){
			$this->components = array_merge($this->components,$conf['plugComponent']);
		}
		
		parent::__construct();
	}
	
	function constructClasses() {
	
		if(in_array('Upgrader',App::objects('plugin')) && !empty($this->params['admin'])) {
			App::import('Lib', 'Upgrader.Upgrader');
			Upgrader::requireUpgraded('Shop',$this);
		}
		
		return parent::constructClasses();
	}
	
	function beforeFilter() {
		parent::beforeFilter();
		
		App::import('Lib', 'Shop.ShopConfig');
		$enabled = ShopConfig::load('enabled');
		if(!$enabled){
			$this->log('Shop is disabled',LOG_DEBUG);
			$this->redirect(array('plugin' => 'auth', 'controller' => 'users', 'action' => 'permission_denied', 'admin' => false));
		}
	}
	/*function dispatchComponentCallback($callback, $params = array()){
		$result = true;
		foreach (array_keys($this->components) as $name) {
			$name = array_pop(explode('.',$name));
			$component =& $this->{$name};
			if (method_exists($component,$callback) && $component->enabled === true) {
				$res = call_user_func_array(array($component, $callback),array_merge(array($this), $params));
				if($res === false){
					$result = false;
				}
			}
		}
		return $result;
	}*/
}
?>