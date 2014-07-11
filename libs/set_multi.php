<?php
class SetMulti {

	// App::import('Lib', 'Shop.SetMulti');
	
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
				if(is_numeric($name)){
					$res[$name] = $val;
				}else{
					$res = Set::insert($res, $name, $val);
				}
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
	function filterNot($array,$callback = null,$lvl=0){
		if($lvl<1){
			$array = array_diff_key($array,array_filter($array,$callback));
		}
		if($lvl != 0){
			foreach($array as &$val){
				if(is_array($val)){
					$val = SetMulti::filterNot($val,$callback,$lvl-1);
				}
			}
		}
		return $array;
	}
	
	function excludeKeys($array,$keys,$recursive = false){
		$array = array_diff_key($array, array_flip($keys));
		if($recursive){
			foreach($array as &$val){
				if(is_array($val)){
					if($recursive !== true){
						$recursive--;
					}
					$val = SetMulti::excludeKeys($val,$keys,$recursive);
				}
			}
		}
		return $array;
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
	function complexMerge($arr1, $arr2, $options=array()) {
		$defOpt = array(
			'depth'=>100,
			'curDepth'=>1,
			'sequences'=>true, // true , false or 'first'
		);
		if(is_int($options)) $options = array('depth'=>$options);
		$opt = array_merge($defOpt,$options);
		return SetMulti::_complexMerge($arr1, $arr2, $opt);
	}
	function _complexMerge($arr1, $arr2, $opt) {
		$r = (array)$arr1;
		foreach ((array)$arr2 as $key => $val)    {
			if ($opt['sequences'] === 'first' && is_int($key)) {
				$r[] = $val;
			} elseif ($opt['curDepth'] < $opt['depth'] && is_array($val) && isset($r[$key]) && is_array($r[$key])) {
				$subOpt = $opt;
				$subOpt['curDepth']++;
				$r[$key] = SetMulti::_complexMerge($r[$key], $val, $subOpt);
			} elseif ($opt['sequences'] && is_int($key)) {
				$r[] = $val;
			} else {
				$r[$key] = $val;
			}
		}
		return $r;
	}
	
	function replaceRef(&$from,$to){
		$tmp = $from;
		foreach($tmp as $key => $val){
			unset($from[$key]);
		}
		$from = array_merge($to,$from);
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
	function insertBeforeKey($array, $key, $data = null, $appendDefault = true)
	{
		if (($offset = array_search($key, array_keys($array))) === false) 
		{
			$offset = $appendDefault ? count($array) : 0;
		}

		return array_merge(array_slice($array, 0, $offset), (array) $data, array_slice($array, $offset));
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
	
	function testCond($cond, $data, $or = false, $empty = false){
		$valid = true;
		$def = $empty;
		$modifKeys = array('and','or');
		App::import('Lib', 'Operations');
		foreach($cond as $key => $cnd){
			$op = false;
			$path = $key;
			if(is_numeric($path)){
				$val = $data;
			}elseif(in_array($path,$modifKeys)){
				$val = $data;
			}elseif(array_key_exists($path,$data)){
				$val = $data[$path];
			}else{
				$op = Operations::parseStringOperation($path,array('mode'=>'left','type'=>'bool','sepPattern'=>'\h+'));
				if($op){
					$path = $op['subject'];
				}
				$val = Set::Extract($path,$data);
			}
			if($op){
				$op['subject'] = $val;
				$op['val'] = $cnd;
				$valid = Operations::applyOperation($op);
			}elseif(is_null($cnd)){
				$valid = is_null($val);
			}elseif(is_array($cnd)){
				$or = ($path == 'or');
				$valid = SetMulti::testCond($cnd,$val,$or);
			}elseif(!is_null($data)){
				$valid = $val == $cnd;
			}else{
				$valid = false;
			}
			if($valid == $or){
				return $or;
			}
			$def = $valid;
		}
		return $def;
		
	}
}
?>