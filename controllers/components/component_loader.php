<?php
class ComponentLoaderComponent extends Object{

	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	function init(&$controller) {
		$this->controller =& $controller;
	}
	
	function loadComponent($componentName, $config = null, $parent = null){
		$component = null;
		$pathing = $this->componentPathing($componentName);
		$created = false;
		if(!isset($this->controller->Component->_loaded[$pathing['name']])){
			if (!class_exists($pathing['className'])) {
				if (is_null($pathing['plugin']) || !App::import('Component', $pathing['fullName'])) {
					if (!App::import('Component', $pathing['name'])) {
						$this->cakeError('missingComponentFile', array(array(
							'className' => $this->controller->name,
							'component' => $pathing['name'],
							'file' => Inflector::underscore($pathing['name']) . '.php',
							'base' => '',
							'code' => 500
						)));
						return false;
					}
				}
	
				if (!class_exists($pathing['className'])) {
					$this->cakeError('missingComponentClass', array(array(
						'className' => $this->controller->name,
						'component' => $pathing['name'],
						'file' => Inflector::underscore($pathing['name']) . '.php',
						'base' => '',
						'code' => 500
					)));
					return false;
				}
			}
			$component =& new $pathing['className']();
			$created = true;
			$component->enabled = true;
			$this->controller->Component->_loaded[$pathing['name']] = $component;
		}else{
			$component =& $this->controller->Component->_loaded[$pathing['name']];
		}
		if (!empty($config)) {
			if(isset($this->controller->Component->__settings[$pathing['name']])) {
				$this->controller->Component->__settings[$pathing['name']] = array_merge($this->controller->Component->__settings[$pathing['name']], $config);
			} else {
				$this->controller->Component->__settings[$pathing['name']] = $config;
			}
		}
		if(!empty($parent)){
			$parent->{$pathing['name']} = $component;
		}
		if(!empty($component->components)){
			$normal = Set::normalize($component->components);
			foreach ((array)$normal as $subcomponent => $config) {
				$this->loadComponent($subcomponent,$config,$component);
			}
		}
		
		if ($created && method_exists($component,'initialize') && $component->enabled === true) {
			$settings = array();
			if (isset($this->controller->Component->__settings[$pathing['name']])) {
				$settings = $this->controller->Component->__settings[$pathing['name']];
			}
			$component->initialize($this->controller, $settings);
		}
		
		return $component;
	}
	
	function componentPathing($componentName){
		$pathing = array(
			'fullName' => null,
			'plugin' => null,
			'className' => null,
			'name' => null,
			'sufix' => 'Component'
		);
		$extract = explode('.', $componentName);
		if(count($extract)>1){
			$pathing['plugin'] = $extract[0];
		}elseif(!empty($this->controller->plugin)){
			$pathing['plugin'] = $this->controller->plugin;
		}
		$pathing['name'] = $extract[count($extract)-1];
		$pathing['fullName'] = $pathing['plugin'].'.'.$pathing['name'];
		$pathing['className'] = $pathing['name'].$pathing['sufix'];
		return $pathing;
	}
}
?>
