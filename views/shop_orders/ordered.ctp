<p><?php __d('shop','Your Order is now completed'); ?></p>
<?php
	
	echo $html->link(__d('shop','Print you order',true),array('target'=>'_blank','action' => 'print_invoice', $order['ShopOrder']['id']));
?>