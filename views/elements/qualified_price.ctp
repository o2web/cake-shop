<?php
if(empty($fullPrice)){
	$fullPrice = $this->Shop->fullPrice(array('dataOnly'=>true));
}
?>
<span class="shopPrice">
	<?php if(!empty($fullPrice['original_price']) && $fullPrice['original_price'] != $fullPrice['price']){ ?>
	<span class="originalPrice"><?php echo $this->Shop->currency($fullPrice['original_price']); ?></span>
	<?php } ?>
	<?php if(!empty($fullPrice['rebate'])){ ?>
	<span class="priceRebate"><?php echo $this->Shop->currency($fullPrice['rebate']*-1); ?></span>
	<?php } ?>
	<span class="price<?php if(!empty($fullPrice['rebate'])) echo " priceReduced" ?>"><?php echo $this->Shop->currency($fullPrice['price']); ?></span>
</span>