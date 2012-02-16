<?php 
class LinkProductComponent extends Object
{
	var $components = array();
	
	var $settings = array(
		'model'=>null,
		'editActions'=>array('admin_edit','admin_add')
	);
	
	var $modelInstance = null;
	
	function initialize(&$controller, $settings = array()) {
		$this->settings = array_merge($this->settings,$settings);
		/*if(empty($this->settings['model'])){
			debug($controller)
		}*/
	}
	
	//called after Controller::beforeFilter()
	function startup(&$controller) {
		if(empty($this->settings['model'])){
			$this->settings['model'] = $controller->modelClass;
		}
		if(isset($controller->{$this->settings['model']})){
			$this->modelInstance = $controller->{$this->settings['model']};
			$this->modelInstance->Behaviors->attach('Shop.Product');
		}
		//debug($controller);
		//debug($this->modelInstance);
	}

	//called after Controller::beforeRender()
	function beforeRender(&$controller) {
		if(in_array($controller->action,$this->settings['editActions'])){
			$addBlocks = array();
			if(isset($controller->viewVars['addBlocks'])){
				$addBlocks = $controller->viewVars['addBlocks'];
			}
			$addBlocks = array_merge($addBlocks,array('product_box'=>array('plugin'=>'shop')));
			$controller->set('addBlocks',$addBlocks);
		}
	}

	//called after Controller::render()
	function shutdown(&$controller) {
	}

	//called before Controller::redirect()
	function beforeRedirect(&$controller, $url, $status=null, $exit=true) {
	}

}
?>