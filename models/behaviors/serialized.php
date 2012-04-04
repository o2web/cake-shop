<?php

class SerializedBehavior extends ModelBehavior {
	
	//var $disable = false;
	//var $tempDisable = false;
	//var $realTable = '';

	function setup(&$Model, $config = array()) {
		$Model->serializeFields = $config;
	}
	
	function beforeSave(&$Model) {
		if(isset($Model->serializeFields)){
			$serializeFields = $Model->serializeFields;
			if(!$serializeFields){
				$serializeFields = array();
			}else if(!is_array($serializeFields) && $serializeFields){
				$serializeFields = array($serializeFields);
			}
			foreach($serializeFields as $field){
				if(isset($Model->data[$Model->alias][$field]) && $Model->data[$Model->alias][$field] != NULL){
					$Model->data[$Model->alias][$field] = serialize($Model->data[$Model->alias][$field]);
				}
			}
		}
		return true;
	}
	
	function afterFind(&$Model, $results, $primary) {
		return $this->unserialize($Model, $results);
	}
	function assocAfterFind(&$Model, $results, $primary) {
		return $this->unserialize($Model, $results);
	}
	
	function serialize(&$Model, $data = null){
		if(is_null($data)){
			$data =& $Model->data;
		}
		if(isset($Model->serializeFields)){
			$serializeFields = $Model->serializeFields;
			if(!$serializeFields){
				$serializeFields = array();
			}else if(!is_array($serializeFields) && $serializeFields){
				$serializeFields = array($serializeFields);
			}
			if(isset($data[$Model->alias])){
				$res =& $data[$Model->alias];
			}else{
				$res =& $data;
			}
			foreach($serializeFields as $field){
				if(isset($res[$field]) && $res[$field] != NULL){
					$res[$field] = serialize($res[$field]);
				}
			}
		}
		return $data;
	}
	
	function unserialize(&$Model, $results = null){
		if(is_null($results)){
			$results =& $Model->data;
		}
		if(isset($Model->serializeFields)){
			$serializeFields = $Model->serializeFields;
			if(!$serializeFields){
				$serializeFields = array();
			}else if(!is_array($serializeFields) && $serializeFields){
				$serializeFields = array($serializeFields);
			}
			if(isset($results[$Model->alias])){
				$res =& $results[$Model->alias];
			}else{
				$res =& $results;
			}
			if(!isset($res[0])){
				$old_res =& $res;
				unset($res);
				$res = array(&$old_res);
			}
			foreach($serializeFields as $field){
				foreach ($res as &$r) {
					if(isset($r[$Model->alias])){
						$r =& $r[$Model->alias];
					}
					if(isset($r[$field]) && !empty($r[$field])){
						$r[$field] = unserialize($r[$field]);
					}
				}
			}
		}
		return $results;
	}
	
	function _array_map_recursive($func, $arr) {
		$newArr = array();
		foreach( $arr as $key => $value ) {
			$newArr[ $key ] = ( is_array( $value ) ? $this->_array_map_recursive( $func, $value ) : $func( $value ) );
		}
		return $newArr;
	}
	
}
?>