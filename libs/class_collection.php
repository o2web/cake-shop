<?php
class ClassCollection extends Object {

	//App::import('Lib', 'ClassCollection'); 
	
	var $types = array(
		'promotions'=>array(
			'classSufix'=>'Promo',
			'fileSufix'=>'_promo',
			'paths'=>'%app%/libs/promo/',
			'ext'=>'php',
			/*'parent'=>array(
				'name'=>'Handler'
			)*/
		),
	);
	var $defaultOptions = array(
		'classSufix'=>null,
		'fileSufix'=>null,
		'paths'=>'%app%/libs/',
		'ext'=>'php',
		'parent'=>null
	);
	var $parentInerit = array(
		'ext','paths'
	);
	
	//$_this =& ClassCollection::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new ClassCollection();
		}
		return $instance[0];
	}
	
	function parseClassName($options){
		$name = Inflector::camelize($options['name']);
		if(!empty($options['classSufix'])){
			$name .= $options['classSufix'];
		}
		return $name;
	}
	
	function setType($type,$options){
		if(!isset($this->types[$type])){
			$this->types[$type] = array();
		}
		$this->types[$type] = Set::Merge($this->types[$type],$options);
	}
	
	function parseImportOption($options){
		$_this =& ClassCollection::getInstance();
		$options = Set::Merge($_this->defaultOptions,$options);
		$importOpt = array(
			'type'=>null,
			'name'=>Inflector::camelize($options['name']),
			'file'=>Inflector::underscore($options['name'])
		);
		if(!empty($options['classSufix'])){
			$importOpt['name'] .= $options['classSufix'];
		}
		if(!empty($options['fileSufix'])){
			$importOpt['file'] .= $options['fileSufix'];
		}
		$importOpt['file'] .= '.'.$options['ext'];
		if(!empty($options['paths'])){
			$importOpt['search'] = $_this->_getPaths($options);
		}
		
		return $importOpt;
	}
	
	function getPaths($typeOpt){
		$_this =& ClassCollection::getInstance();
		$opt = Set::Merge($_this->defaultOptions,$typeOpt);
		return $_this->_getPaths($opt);
	}
	function _getPaths($typeOpt){
		$paths = array();
		if(!empty($typeOpt['paths'])){
			foreach((array)$typeOpt['paths'] as $path){
				$path = str_replace('%app%',APP,$path);
				$path = str_replace('/',DS,$path);
				$path = str_replace(DS.DS,DS,$path);
				$paths[] = $path;
			}
		}
		return $paths;
	}
	
	function getList($type,$named=false){
		$_this =& ClassCollection::getInstance();
		$opt = $_this->types[$type];
		$opt = Set::Merge($_this->defaultOptions,$opt);
		
		$paths = $_this->_getPaths($opt);
		
		$endsWith = $opt['fileSufix'].'.'.$opt['ext'];
		
		$items = array();
		foreach($paths as $path){
			$Folder =& new Folder($path);
			$contents = $Folder->read(false, true);
			foreach ($contents[1] as $item) {
				if (substr($item, - strlen($endsWith)) === $endsWith) {
					$item = substr($item, 0, strlen($item) - strlen($endsWith));
					if($named){
						$items[$item] = Inflector::humanize($item);
					}else{
						$items[] = $item;
					}
				}
			}
		}
		return $items;
	}
	
	function getObject($type,$name){
		$_this =& ClassCollection::getInstance();
		$options['name'] = $name;
		$class = $_this->parseClassName($options);
		$exitant = ClassRegistry::getObject($type.'.'.$name);
		if($exitant){
			return $exitant;
		}else{
			$class = $_this->getClass($type,$name);
		}
		//debug($class);
		if(!empty($class) && class_exists($class) ) {
			$created = new $class();
			if($created){
				$success = ClassRegistry::addObject($type.'.'.$name, $created);
			}
			return $created;
		}
		return null;
	}
	
	
	function getClass($type,$name){
		$_this =& ClassCollection::getInstance();
		$options = array();
		if(is_array($name)){
			$options = $name;
		}else{
			$options['name'] = $name;
		}
		
		if(!empty($_this->types[$type])){
			$options = Set::Merge($_this->types[$type],$options);
		}
		
		
		if(!empty($options['parent'])){
			$inerit = array_intersect_key($options,array_flip($_this->parentInerit));
			$parentOpt = Set::Merge($inerit,$options['parent']);
			$parent = $_this->getClass(null,$parentOpt);
			if(empty($parent)){
				return null;
			}
		};
		
		$importOpt = $_this->parseImportOption($options);
		
		if(App::import($importOpt)){
			return $importOpt['name'];
		}else{
			debug($importOpt['name']. ' not found.');
			return null;
		}
		
	}
}
?>