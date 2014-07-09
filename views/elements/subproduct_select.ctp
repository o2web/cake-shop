<?php 
//if($type['max'] == 1 && $type['maxQtyEach'] == 1){
App::import('Lib', 'Shop.SetMulti');
//debug($product);
$choices = $subProducts;
$choices = SetMulti::group($choices,'id',array('singleArray'=>false,'valPath'=>'label'));
if(count($choices) > 1 || $type['min'] == 0){
	if($type['min'] == 0){
		$options['empty'] = __('None',true);
	}
	if(($type['max'] === false || $type['max'] > 1) && count($choices) > 1){
		$options['multiple'] = true;
	}
	$options['options'] = $choices;
	echo $this->Form->input($name,$options);
}else{
	$onlyOpt = each($choices);
	echo '<div class="'.$type['name'].'">'.$onlyOpt['value'].'</div>';
	echo $this->Form->input($name,array('type'=>'hidden','value'=>$onlyOpt['key']));
}
//}
echo implode("\n",$children);

?>