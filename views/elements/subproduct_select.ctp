<?php 

if($type['max'] == 1 && $type['maxQtyEach'] == 1){
	App::import('Lib', 'Shop.SetMulti');
	//debug($product);
	$choices = $product['ShopSubproduct'];
	$choices = SetMulti::group($choices,'id',array('singleArray'=>false,'valPath'=>'label'));
	echo $this->Form->input('ShopCart'.(isset($cartPos)?'.products.'.$cartPos:'').'.SubItem.'.$type['name'],array('options'=>$choices,'label'=>false));
}

?>