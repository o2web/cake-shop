<?php
class ClassCollection extends Object {

	//App::import('Lib', 'ClassCollection'); 
	
	var $types = array(
		'promo'=>array(
			'classSufix'=>'Promo',
			'fileSufix'=>'_promo',
			'paths'=>'%app%/libs/promo/',
			'ext'=>'php',
			'parent'=>array(
				'plugin'=>'Shop',
				'name'=>'PromoMethod'
			)
		),
	);
	var $defaultOptions = array(
		'classSufix'=>null,
		'fileSufix'=>null,
		'paths'=>'%app%/libs/',
		'ext'=>'php',
		'parent'=>null,
		'pluginPassthrough'=>false,
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
	
	function parseName($name){
		$options['name'] = $name;
		$parts = explode('.',$name);
		if(count($parts) > 1){
			$options['plugin'] = $parts[0];
			$options['name'] = $parts[1];
		}
		return $options;
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
	function _getPaths($typeOpt,$namedPlugin = false){
		$paths = array();
		if(!empty($typeOpt['paths'])){
			if(empty($typeOpt['plugin'])){
				if((!isset($typeOpt['plugin']) || $typeOpt['plugin'] !== false) && ($typeOpt['pluginPassthrough'] || $namedPlugin)){
					$plugins = array_merge(array(null),App::objects('plugin'));
				}else{
					$plugins = array(null);
				}
			}elseif(!is_array($typeOpt['plugin'])){
				$plugins = array($typeOpt['plugin']);
			}
			foreach((array)$typeOpt['paths'] as $path){
				foreach($plugins as $p){
					if(empty($p)){
						$app = APP;
						$p = 'app';
					}else{
						$app = App::pluginPath($p);
					}
					if(!empty($app)){
						$ppath = str_replace('%app%',$app,$path);
						$ppath = str_replace('/',DS,$ppath);
						$ppath = str_replace(DS.DS,DS,$ppath);
						if($namedPlugin){
							$paths[$p][] = $ppath;
						}else{
							$paths[] = $ppath;
						}
					}
				}
			}
		}
		return $paths;
	}
	
	function getList($type,$named=false){
		$_this =& ClassCollection::getInstance();
		$opt = $_this->types[$type];
		$opt = Set::Merge($_this->defaultOptions,$opt);
		
		$ppaths = $_this->_getPaths($opt,true);
		//debug($ppaths);
		
		$endsWith = $opt['fileSufix'].'.'.$opt['ext'];
		
		$items = array();
		foreach($ppaths as $plugin => $paths){
		foreach($paths as $path){
			$Folder =& new Folder($path);
			$contents = $Folder->read(false, true);
			foreach ($contents[1] as $item) {
				if (substr($item, - strlen($endsWith)) === $endsWith) {
					$item = substr($item, 0, strlen($item) - strlen($endsWith));
					if($named){
							if($plugin != 'app'){
								if($named === 'flat'){
									$items[$plugin.'.'.$item] = Inflector::humanize($item);
								}else{
									$items[$plugin][$plugin.'.'.$item] = Inflector::humanize($item);
								}
							}else{
						$items[$item] = Inflector::humanize($item);
							}
						}else{
							if($plugin != 'app'){
								$items[] = $plugin.'.'.$item;
					}else{
						$items[] = $item;
					}
				}
			}
		}
			}
		}
		return $items;
	}
	
	function getObject($type,$name){
		$_this =& ClassCollection::getInstance();
		$options = $_this->parseName($name);
		$class = $_this->parseClassName($options);
		$exitant = ClassRegistry::getObject($type.'.'.$name);
		if($exitant){
			return $exitant;
		}else{
			$class = $_this->getClass($type,$options);
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
			$options = $_this->parseName($name);
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
		//debug($importOpt);
		
		if(App::import($importOpt)){
			return $importOpt['name'];
		}else{
			debug($importOpt['name']. ' not found.');
			return null;
		}
		
	}
}
?>