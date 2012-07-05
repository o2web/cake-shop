<?php
	$this->Html->css('/shop/css/shop.admin',null,array('inline'=>false));
?>
<div class="shopOrders index">
	<?php
		echo $this->Form->create('Shop Order', array('class' => 'search', 'url' => array('action' => 'list_input')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Commandes non-complétés');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>		
            <th><?php __d('shop','Products');?></th>				
			<th><?php echo $this->Paginator->sort('sub_total');?></th>
			<th><?php __('Étape d\'abandon'); ?></th>
			<th><?php __d('shop','Billing Address');?></th>
            <th><?php __d('shop','Shipping Address');?></th>	
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true));
			foreach ($shopOrders as $shopOrder) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $shopOrder['ShopOrder']['id']; ?>&nbsp;</td>
						<td><?php 
							echo $this->element('admin_list_items',array('plugin'=>'shop','shopOrder'=>$shopOrder));
							?>&nbsp;</td>
						<td class="sub_total"><?php 
							if(!empty($shopOrder['ShopOrdersItem'])){
								$subTotal = 0;
								foreach($shopOrder['ShopOrdersItem'] as $orderItem){ 
									$subTotal += $orderItem['item_price'] * $orderItem['nb'];
								} 
								echo $subTotal;
							}
							?>&nbsp;</td>
						<td class="status"><?php 
							if(empty($shopOrder['ShopOrder']['billing_address'])){
								echo __('Adresse de livraison et de facturation');
							}elseif(empty($shopOrder['ShopOrder']['confirm'])){
								echo __('Confirmation');
							}else{
								echo __('???');
							}
						?>&nbsp;</td>
						<td><?php echo $this->element('address',array('plugin'=>'shop','address'=>$shopOrder['ShopOrder'],'prefix'=>'billing_'))?></td>		
            			<td><?php echo $this->element('address',array('plugin'=>'shop','address'=>$shopOrder['ShopOrder'],'prefix'=>'shipping_'))?></td>	
						<td class="actions">
							<?php //echo $this->Html->link(__('Edit', true), array('action' => 'edit', $shopOrder['ShopOrder']['id']), array('class' => 'edit')); ?>
							<?php
								echo $html->link(
									'<span>'.__('View', true).'</span>', 
									array('action' => 'view', $shopOrder['ShopOrder']['id']), 
									array('class'=>'icon view','escape' => false)
								);
								
								echo $html->link(
									'<span>'.__('Annuler la commande', true).'</span>', 
									array('action' => 'cancel', $shopOrder['ShopOrder']['id']), 
									array('class'=>'icon delete','escape' => false), 
									sprintf(__('Voulez-vous vraiment annuler cette commande ?', true), $shopOrder['ShopOrder']['id'])
								);
							?>
						</td>
					</tr>
				<?php
			}
		?>
	</table>
	
	<p class="paging">
		<?php
			echo $this->Paginator->counter(array(
				'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
			));
		?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('« '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 |
		<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' »', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<ul>
		<!--
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders Items', true)), array('controller' => 'shop_orders_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Orders Item', true)), array('controller' => 'shop_orders_items', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Products', true)), array('controller' => 'shop_products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Product', true)), array('controller' => 'shop_products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Payments', true)), array('controller' => 'shop_payments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Payment', true)), array('controller' => 'shop_payments', 'action' => 'add')); ?> </li>
		-->
	</ul>
</div>