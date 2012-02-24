<?php 
//debug($type);
if($type['max'] == 1 && $type['maxQtyEach'] == 1){
	App::import('Lib', 'Shop.SetMulti');
	//debug($product);
	$choices = $subProducts;
	$choices = SetMulti::group($choices,'id',array('singleArray'=>false,'valPath'=>'label'));
	$options['options'] = $choices;
	echo $this->Form->input($name,$options);
}

?>