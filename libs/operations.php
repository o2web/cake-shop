<?php
class Operations extends Object {

	// App::import('Lib', 'Shop.Operations');

	var $opDefOpt = array(
		'function' => true,
		'named' => array(
			0 => array('val')
		)
	);
	var $operators = array(
		'add' => array(
			'name' => 'add',
			'pattern' => '/^\+\h*([0-9]*,?[0-9]+)$/',
			'alias' => array('+=','+')
		),
		'substract' => array(
			'name' => 'substract',
			'pattern' => '/^--\h*([0-9]*,?[0-9]+)$/',
			'alias' => array('-=','-')
		),
		'addPrc' => array(
			'name' => 'addPrc',
			'pattern' => '/^\+\h*([0-9]*,?[0-9]+)%$/',
			'alias' => array('+%')
		),
		'substractPrc' => array(
			'name' => 'substractPrc',
			'pattern' => '/^--\h*([0-9]*,?[0-9]+)%$/',
			'alias' => array('-%')
		),
		'multiply' => array(
			'name' => 'multiply',
			'pattern' => '/^\*\h*([0-9]*,?[0-9]+)$/',
			'alias' => array('*=','*')
		),
		'concat' => array(
			'name' => 'concat',
			'pattern' => '/^\.\h*.+$/',
			'alias' => array('.=','.')
		),
		'greater' => array(
			'name' => 'greater',
			'pattern' => '/^>\h*.+$/',
			'alias' => array('>','gt')
		),
		'greaterEq' => array(
			'name' => 'greaterEq',
			'pattern' => '/^>=\h*.+$/',
			'alias' => array('>=','gte')
		),
		'lesser' => array(
			'name' => 'lesser',
			'pattern' => '/^<\h*.+$/',
			'alias' => array('<','lt')
		),
		'lesserEq' => array(
			'name' => 'lesser',
			'pattern' => '/^<=\h*.+$/',
			'alias' => array('<=','lte')
		),
		'equal' => array(
			'name' => 'equal',
			'pattern' => '/^==?\h*.+$/',
			'alias' => array('=','eq','==')
		),
		'equalAbs' => array(
			'name' => 'equalAbs',
			'pattern' => '/^===\h*.+$/',
			'alias' => array('===')
		),
		'different' => array(
			'name' => 'different',
			'pattern' => '/^(!=)|(<>)\h*.+$/',
			'alias' => array('!=','diff','<>')
		),
		'differentAbs' => array(
			'name' => 'differentAbs',
			'pattern' => '/^!==\h*.+$/',
			'alias' => array('!==')
		),
	);

	
	//$_this =& Operations::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new Operations();
		}
		return $instance[0];
	}
	
	function _op_add($val,$val2){
		return $val + $val2;
	}
	function _op_substract($val,$val2){
		return $val - $val2;
	}
	function _op_addPrc($val,$val2){
		return $val * ($val2/100+1);
	}
	function _op_substractPrc($val,$val2){
		return $val * (1-$val2/100);
	}
	function _op_multiply($val,$val2){
		return $val * $val2;
	}
	function _op_concat($val,$val2){
		return $val . $val2;
	}
	function _op_greater($val,$val2){
		return $val > $val2;
	}
	function _op_greaterEq($val,$val2){
		return $val >= $val2;
	}
	function _op_lesser($val,$val2){
		return $val < $val2;
	}
	function _op_lesserEq($val,$val2){
		return $val <= $val2;
	}
	function _op_equal($val,$val2){
		return $val == $val2;
	}
	function _op_equalAbs($val,$val2){
		return $val === $val2;
	}
	function _op_different($val,$val2){
		return $val != $val2;
	}
	function _op_differentAbs($val,$val2){
		return $val !== $val2;
	}
	
	function applyOperation($op,$val,$parseString = false){
		//debug($op);
		$_this =& Operations::getInstance();
		if($parseString && is_string($op)){
			$op = $_this->parseStringOperation($op);
		}
		$opOpt = $_this->getOperator($op);
		if($opOpt){
			App::import('Lib', 'SetMulti'); 
			//debug($opOpt);
			$params = SetMulti::extractHierarchicMulti($opOpt['named'], $op);
			//debug($params);
			$directParams = SetMulti::pregFilterKey('/^[0-9]+$/',$op);
			//debug($directParams);
			$params = $directParams + $params;
			array_unshift($params,$val);
			//debug($params);
			if((isset($opOpt['function']) && $opOpt['function'] === true) ){
				$funct = array($_this,'_op_'.$opOpt['name']);
			}elseif(!empty($opOpt['function'])){
				$funct = $opOpt['function'];
			}
			if(!empty($funct) && is_callable($funct)){
				return call_user_func_array($funct,$params);
			}
		}
		return null;
	}
	
	function simpleOperation($val1,$op,$val2){
		$_this =& Operations::getInstance();
		$opt = array('operator' => $op, 'val' => $val2);
		return $_this->applyOperation($opt,$val1);
	}
	
	function applyOperations($data,$preData,$parseString = false){
		$_this =& Operations::getInstance();
		foreach($cp = $data as $key => $val){
			if(is_array($val) && !empty($val['operator'])){
				if(isset($preData[$key])){
					$res = $_this->applyOperation($val,$preData[$key]);
					if(is_null($res)){
						unset($data[$key]);
					}else{
						$data[$key] = $res;
					}
				}else{
					unset($data[$key]);
				}
			}
		}
		return $data;
	}
	
	function getOperator($opt){
		$_this =& Operations::getInstance();
		if(!is_array($opt)){
			$opt = array('operator' => $opt);
		}
		$op = null;
		if(isset($_this->operators[$opt['operator']])){
			$op = $_this->operators[$opt['operator']];
		}else{
			foreach($_this->operators as $oper){
				if(!empty($oper['alias']) && in_array($opt['operator'],$oper['alias'])){
					$op = $oper;
					break;
				}
			}
		}
		if($op){
			$op = Set::merge($_this->opDefOpt,$op);
		}
		return $op;
	}
	
	function parseStringOperations($data){
		$_this =& Operations::getInstance();
		foreach($data as $key => $val){
			foreach($_this->operators as $op){
				$data[$key] = $_this->parseStringOperation($val);
			}
		}
		return $data;
	}
	
	function parseStringOperation($val){
		$_this =& Operations::getInstance();
		foreach($_this->operators as $op){
			$op = Set::merge($_this->opDefOpt,$op);
			if(!empty($op['pattern'])){
				if(preg_match($op['pattern'],(string)$val,$res)){
					$oper = array('operator' => $op['name']);
					array_shift($res);
					foreach($res as $k => $r){
						if(!empty($op['named'][$k])){
							$name = (array)$op['named'][$k];
							$oper[$name[0]] = $r;
						}else{
							$oper[$k] = $r;
						}
					}
					return $oper;
				}
			}
		}
		return $val;
	}
}
?>