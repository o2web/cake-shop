<div class="shopOrders view">
<h2><?php  __('Shop Order');?></h2>
	<?php
	echo $this->element('invoice', array('order'=>$shopOrder, 'plugin'=>'shop')); ?>
</div>
