<?php
class ShopAppController extends AppController {
	var $pluginVersion = "0.1.4.0";
	
	function __construct() {
		//////// config ////////
		App::import('Lib', 'Shop.ShopConfig');
		ShopConfig::load();
		
		parent::__construct();
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