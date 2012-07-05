<?php
	$this->Html->css('/shop/css/shop.admin',null,array('inline'=>false));
?>
<div class="shopOrders index">
	<?php
		echo $this->Form->create('Shop Order', array('class' => 'search', 'url' => array('action' => 'list')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
		
		$bool = array(__('No', true), __('Yes', true));
	?>	
	<h2><?php __('Shop Orders');?></h2>
	
	
	
	<div class="orderSummaryList orderSummaryReadyList"><div class="orderSummaryListContent">
		<h3><?php __('Dernières commandes en attente de Payment ou de confirmation de Payment');?></h3>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th><?php __('id');?></th>		
				<th><?php __d('shop','Products');?></th>	
				<th><?php __('total');?></th>	
				<th><?php __d('shop','Billing Address');?></th>	
				<th><?php __('Date');?></th>
				<th class="actions"><?php __('Actions');?></th>
			</tr>
			<?php
				$i = 0;
				foreach ($readyOrders as $shopOrder) {
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
							<td class="total"><?php echo $shopOrder['ShopOrder']['total']; ?>&nbsp;</td>
							<td><?php echo $this->element('address',array('plugin'=>'shop','address'=>$shopOrder['ShopOrder'],'prefix'=>'billing_'))?></td>		
							<td class="date"><?php echo $shopOrder['ShopOrder']['date']; ?>&nbsp;</td>
							<td class="actions">
								<?php //echo $this->Html->link(__('Edit', true), array('action' => 'edit', $shopOrder['ShopOrder']['id']), array('class' => 'edit')); ?>
								<?php
									echo $html->link(
										'<span>'.__('View', true).'</span>', 
										array('action' => 'view', $shopOrder['ShopOrder']['id']), 
										array('class'=>'icon view','escape' => false)
									);
									
									echo $html->link(
										'<span>'.__('Confirmer le paiment manuellement', true).'</span>', 
										array('plugin'=>'shop', 'controller' => 'shop_payments', 'action' => 'add', 'order' => $shopOrder['ShopOrder']['id']), 
										array('class'=>'icon confirm','escape' => false)
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
		<a href="<?php echo $this->Html->url(array('action'=>'list_ready')); ?>"><?php __('Voir toutes les commandes en attente'); ?></a>
	</div></div>
	
	
	<div class="orderSummaryList"><div class="orderSummaryListContent">
		<h3><?php __('Dernières commandes Complétés');?></h3>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th><?php __('id');?></th>		
				<th><?php __d('shop','Products');?></th>
				<th><?php __('total');?></th>
				<th><?php __d('shop','Billing Address');?></th>
				<th><?php __d('shop','Shipping Address');?></th>	
				<th><?php __('Date');?></th>		
				<th class="actions"><?php __('Actions');?></th>
			</tr>
			<?php
				$i = 0;
				foreach ($OrderedOrders as $shopOrder) {
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
							<td class="total"><?php echo $shopOrder['ShopOrder']['total']; ?>&nbsp;</td>
							<td><?php echo $this->element('address',array('plugin'=>'shop','address'=>$shopOrder['ShopOrder'],'prefix'=>'billing_'))?></td>		
							<td><?php echo $this->element('address',array('plugin'=>'shop','address'=>$shopOrder['ShopOrder'],'prefix'=>'shipping_'))?></td>
							<td class="date"><?php echo $shopOrder['ShopOrder']['date']; ?>&nbsp;</td>
							<td class="actions">
								<?php //echo $this->Html->link(__('Edit', true), array('action' => 'edit', $shopOrder['ShopOrder']['id']), array('class' => 'edit')); ?>
								<?php echo $this->Html->link(__('View', true), array('action' => 'view', $shopOrder['ShopOrder']['id']), array('class' => 'view')); ?>
							</td>
						</tr>
					<?php
				}
			?>
		</table>
		<a href="<?php echo $this->Html->url(array('action'=>'list_ordered')); ?>"><?php __('Voir toutes les commandes complétés'); ?></a>
	</div></div>
	
	
	
	<div class="orderSummaryList"><div class="orderSummaryListContent">
		<h3><?php __('Dernières commandes non-complétés');?></h3>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<th><?php __('Id');?></th>		
				<th><?php __d('shop','Products');?></th>	
				<th><?php __('Sub Total');?></th>	
				<th><?php __('Étape d\'abandon'); ?></th>
				<th><?php __d('shop','Billing Address');?></th>	
				<th><?php __('Modified');?></th>	
				<th class="actions"><?php __('Actions');?></th>
			</tr>
			<?php
				$i = 0;
				foreach ($inputOrders as $shopOrder) {
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
							<td class="total"><?php 
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
							<td class="modified"><?php echo $shopOrder['ShopOrder']['modified']; ?>&nbsp;</td>
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
		<a href="<?php echo $this->Html->url(array('action'=>'list_input')); ?>"><?php __('Voir toutes les commandes non-complétés'); ?></a>
	</div></div>
	
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