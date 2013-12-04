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
		'plugin'=>null,
		'classSufix'=>null,
		'fileSufix'=>null,
		'paths'=>'%app%/libs/',
		'ext'=>'php',
		'parent'=>null,
		'pluginPassthrough'=>false,
		'defaultByParent'=>false,
		'throwException'=>true,
		'setName'=>false,
		'setPlugin'=>false,
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
		
		if(strpos($options['name'],'.') !== false){
			list($options['plugin'],$options['name']) = explode('.',$options['name'],2);
		}
		$importOpt = array(
			'type'=>null,
			'name'=>$_this->parseClassName($options),
			'file'=>Inflector::underscore($options['name'])
		);
		if(!empty($options['fileSufix'])){
			$importOpt['file'] .= $options['fileSufix'];
		}
		$importOpt['file'] .= '.'.$options['ext'];
		if(!empty($options['paths'])){
			$importOpt['search'] = $_this->_getPaths($options);
		}
		//debug($importOpt);
		
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
			}else{
				$plugins = $typeOpt['plugin'];
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
	
	function getOption($type,$name){
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
		$options = Set::Merge($_this->defaultOptions,$options);
		
		if(is_null($options['plugin']) && strpos($options['name'],'.') !== false){
			list($options['plugin'],$options['name']) = explode('.',$options['name'],2);
		}
		
		$options['name'] = Inflector::camelize($options['name']);
		
		return $options;
	}
	
	function getObject($type,$name){
		$_this =& ClassCollection::getInstance();
		
		$options = $_this->getOption($type,$name);
		
		$exitant = ClassRegistry::getObject($type.'.'.$name);
		if($exitant){
			return $exitant;
		}
		$isParent = false;
		$class = $_this->getClass($type,$options,$isParent);
		//debug($class);
		if(!empty($class) && class_exists($class) ) {
			$created = new $class();
			if($options['setName'] && empty($created->name)){
				$created->name = $options['name'];
			}
			if($options['setPlugin'] && !isset($created->plugin)){
				$created->plugin = $options['plugin'];
			}
			if($created && !$isParent){
				$success = ClassRegistry::addObject($type.'.'.$name, $created);
			}
			return $created;
		}
		return null;
	}
	
	
	function getClass($type,$name,&$isParent = false){
		$_this =& ClassCollection::getInstance();
		$options = $_this->getOption($type,$name);
		
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
			if(!empty($parent) && $options['defaultByParent']){
				$isParent = true;
				return $parent;
			}
			if($options['throwException']){
			debug($importOpt['name']. ' not found.');
			}
			return null;
		}
		
	}
}
?>