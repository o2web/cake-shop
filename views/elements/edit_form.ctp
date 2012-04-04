<?php if($opt['fieldset']){ ?>
<fieldset>
	<legend><?php echo $opt['legend'] ?></legend>
<?php } ?>
<?php
	echo '	'.$this->Form->input('ShopProduct.price')."\n";
	echo '	'.$this->O2form->input('ShopProduct.currency_prices',array('type'=>'multiple','fields'=>array('price','currency'),'div'=>array('class'=>'type')))."\n";
 
	if(!empty($typeFields)){
?>
		<div class="shopSubProductSelector">
			<p class="label"><?php __('subProduct') ?></p>
			<?php
			foreach($types as $key =>$options){
				$html .= '				'.$this->O2form->input($key,$options)."\n";
			}
			?>
		
		<div>
<?php 
	}
?>		
		
<?php if($opt['fieldset']){ ?>
</fieldset>
<?php } ?>