<?php
class Alias {
	var $country = array(
		'canada' => 'CA',
		'etats_unis' => 'US',
		'united_states' => 'US'
	);
	var $region = array(
		'ontario' => 'ON',
		'quebec' => 'QC',
		'nova_Scotia' => 'NS',
		'nouvelle_ecosse' => 'NS',
		'new_Brunswick' => 'NB',
		'nouveau_brunswick' => 'NB',
		'manitoba' => 'MB',
		'british_columbia' => 'BC',
		'colombie_britannique' => 'BC',
		'prince_edward_island' => 'PE',
		'ile_du_prince_edouard' => 'PE',
		'saskatchewan' => 'SK',
		'alberta' => 'AB',
		'newfoundland_and_labrador' => 'NL',
		'newfoundland' => 'NL',
		'terre_neuve_et_labrador' => 'NL',
		'terre_neuve' => 'NL',
		'northwest_territories' => 'NT',
		'territoires_du_nord_ouest' => 'NT',
		'yukon' => 'YT',
		'nunavut' => 'NU'
	);
	
	
	//$_this =& Alias::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new Alias();
		}
		return $instance[0];
	}
	
	function applyAlias($value,$type){
		$_this =& Alias::getInstance();
		if(!empty($_this->{$type})){
			$normal = strtolower(Inflector::slug($value));
			if(!empty($_this->{$type}[$normal])){
				return $_this->{$type}[$normal];
			}
		}
		return $value;
	}
	
	function applyAliasMulti($values,$assoc){
		$_this =& Alias::getInstance();
		foreach($assoc as $key=>$type){
			if(isset($values[$key])){
				$values[$key] = $_this->applyAlias($values[$key],$type);
			}
		}
		return $values;
	}
}
?>