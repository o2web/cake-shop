<?php
class QtyFilterPromo extends PromoMethod {
	var $type = 'condition';
	
	function editForm($prefix,$view){
		$out = '';
		$out .= $view->Form->input($prefix.'.min',array('type'=>'text','label'=>__('Minimum Quantity',true)));
		$out .= $view->Form->input($prefix.'.max',array('type'=>'text','label'=>__('Maximum Quantity',true)));
		return $out;
	}
	
	function validate($prod,$order){
		return !empty($prod['Options']['nb'])
			&& (!is_numeric($this->params['min']) || $prod['Options']['nb'] >= $this->params['min'])
			&& (!is_numeric($this->params['max']) || $prod['Options']['nb'] <= $this->params['max']);
	}
}