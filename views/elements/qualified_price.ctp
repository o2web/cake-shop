<?php
$source = compact('product','shopProduct');
$extract_data = array(
	'original_price' => array(
		'product.ShopProduct.DynamicField.original_price','product.DynamicField.original_price','product.item_original_price',
		'shopProduct.ShopProduct.DynamicField.original_price','shopProduct.DynamicField.original_price','shopProduct.item_original_price'
	),
	'rebate' => array(
		'product.ShopProduct.DynamicField.rebate','product.DynamicField.rebate','product.item_rebate',
		'shopProduct.ShopProduct.DynamicField.rebate','shopProduct.DynamicField.rebate','shopProduct.item_rebate'
	),
	'price' => array(
		'product.ShopProduct.DynamicField.price','product.DynamicField.price','product.item_price',
		'shopProduct.ShopProduct.DynamicField.price','shopProduct.DynamicField.price','shopProduct.item_price'
	),
);
App::import('Lib', 'Shop.SetMulti');
$data = SetMulti::extractHierarchicMulti($extract_data,$source,array('extractNull'=>false));
?>
<div class="shopPrice">
	<?php if(!empty($data['original_price']) && $data['original_price'] != $data['price']){ ?>
	<span class="originalPrice"><?php echo $this->Number->currency($data['original_price'], $lang); ?></span>
	<?php } ?>
	<?php if(!empty($data['rebate'])){ ?>
	<span class="priceRebate"><?php echo $this->Number->currency($data['rebate']*-1, $lang); ?></span>
	<?php } ?>
	<span class="price<?php if(!empty($data['rebate'])) echo " priceReduced" ?>"><?php echo $this->Number->currency($data['price'], $lang); ?></span>
</div>