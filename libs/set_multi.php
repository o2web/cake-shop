<?php
class SetMulti {
	function extractHierarchic($paths, $data = null, $options = array()) {
		$defaultOptions = array(
			'allowEmptyString' => false,
			'allowFalse' => true,
			'allowZero' => true
		);
		$options = array_merge($defaultOptions,$options);
		foreach((array)$paths as $path){
			$val = Set::extract($path, $data, $options);
			
			if(!is_null($val) 
				&& ($val!=='' || $options['allowEmptyString']) 
				&& ($val!==false || $options['allowFalse']) 
				&& ($val!==0 || $options['allowZero'])
			){
				return $val;
			}
		}
		return null;
	}
	function extractHierarchicMulti($pathsAssoc, $data = null, $options = array()) {
		$defaultOptions = array(
			'extractNull' => true,
		);
		$options = array_merge($defaultOptions,$options);
		$res = array();
		foreach($pathsAssoc as $name => $paths){
			$val = SetMulti::extractHierarchic($paths, $data, $options);
			if(!is_null($val) || $options['extractNull']){
				$res[$name] = $val;
			}
		}
		return $res;
	}
	function isAssoc($array) {
		return (is_array($array) && (count($array)==0 || 0 !== count(array_diff_key($array, array_keys(array_keys($array))) )));
	} 
	
	function pregFilter($pattern,$array){
		$res = array();
		foreach($array as $key => $val){
			if(preg_match($pattern,$val) ){
				if(is_int($key)){
					$res[] = $val;
				}else{
					$res[$key] = $val;
				}
			}
		}
		return $res;
	}
	function pregFilterKey($pattern,$array){
		$res = array();
		foreach($array as $key => $val){
			if(preg_match($pattern,$key) ){
				$res[$key] = $val;
			}
		}
		return $res;
	}
	
	function merge2($arr1, $arr2 = null) {
		$args = func_get_args();

		$r = (array)current($args);
		while (($arg = next($args)) !== false) {
			foreach ((array)$arg as $key => $val)	 {
				if (is_int($key)) {
					$r[] = $val;
				} elseif (is_array($val) && isset($r[$key]) && is_array($r[$key])) {
					$r[$key] = SetMulti::merge2($r[$key], $val);
				} else {
					$r[$key] = $val;
				}
			}
		}
		return $r;
	}
	
	function replaceTree($search="", $replace="", $array=false, $keys_too=false){
		if (!is_array($array)) {
			// Regular replace
			if(!is_null($array)){
				return str_replace($search, $replace, $array);
			}else{
				return $array;
			}
		}

		$newArr = array();
		foreach ($array as $k=>$v) {
		// Replace keys as well?
		$add_key = $k;
		if ($keys_too) {
		  $add_key = str_replace($search, $replace, $k);
		}

		// Recurse
		$newArr[$add_key] = SetMulti::replaceTree($search, $replace, $v, $keys_too);
		}
		return $newArr;
	}
	
	//like array flip but many values with the same key will make have an array of those keys as new value
	function flip($arr){
		$result = array();
		foreach($arr as $key => $val){
			if(!is_string($val) && is_integer($val)){
				$val = (string)$val;
			}
			if(isset($result[$val])){
				$result[$val] = (array)$result[$val];
				$result[$val][] = $key;
			}else{
				$result[$val] = $key;
			}
		}
		return $result;
	}
	
	function group($arr,$keyPath,$opt = array()){
		$defaultOptions = array(
			'singleArray' => true,
			'keepKeys' => true,
			'valPath' => false,
		);
		$opt = array_merge($defaultOptions,$opt);
		$result = array();
		foreach($arr as $key => $val){
			$nval = $val;
			if($opt['valPath']){
				$nval = Set::extract($opt['valPath'], $val);
			}
			$gkey = Set::extract($keyPath, $val);
			if(is_numeric($key) || !$opt['keepKeys']){
				if(isset($result[$gkey])){
					$key = count((array)$result[$gkey]);
				}else{
					$key = 0;
				}
			}
			if($opt['singleArray']){
				$result[$gkey][$key] = $nval;
			}elseif(isset($result[$gkey]) ){
				$result[$gkey] = (array)$result[$gkey];
				$result[$gkey][$key] = $nval;
			}else{
				$result[$gkey] = $nval;
			}
		}
		return $result;
	}
	
	function extractKeepKey($path,$data){
		$out = array();
		foreach($data as $key => $val){
			$ext = Set::extract($path,$val);
			if(!empty($ext)){
				$out[$key] = $ext;
			}
		}
		return $out;
	}
	
	function threadedToList($treaded, $keyPath, $valPath, $spacer = "  ",$lvl = 0){
		$out = array();
		foreach($treaded as $item){
			$out[Set::extract($keyPath,$item)] = str_repeat($spacer,$lvl).Set::extract($valPath,$item);
			if(!empty($item['children'])){
				$out = array_merge($out,SetMulti::threadedToList($item['children'], $keyPath, $valPath, $spacer,$lvl+1));
			}
		}
		return $out;
	}
	
	function threadedToLeveled($treaded, $lvl = 0){
		$out = array();
		foreach($treaded as $item){
			$children = null;
			if(!empty($item['children'])){
				$children = $item['children'];
			}
			unset($item['children']);
			$item['lvl'] = $lvl;
			$out[] = $item;
			if(!empty($children)){
				$out = array_merge($out,SetMulti::threadedToLeveled($children, $lvl+1));
			}
		}
		return $out;
	}
}
?>