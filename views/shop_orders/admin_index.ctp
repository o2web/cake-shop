<div class="shopOrders index">
	<?php
		echo $this->Form->create('Shop Order', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Shop Orders');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>		
            <th><?php __d('shop','Products');?></th>	
			<th><?php echo $this->Paginator->sort('discount');?></th>			
			<th><?php echo $this->Paginator->sort('discount_prc');?></th>			
			<th><?php echo $this->Paginator->sort('sub_total');?></th>			
			<th><?php echo $this->Paginator->sort('taxes');?></th>	
			<th><?php echo $this->Paginator->sort('total');?></th>	
			<th><?php echo $this->Paginator->sort('status');?></th>
			<th><?php __d('shop','Billing Address');?></th>
            <th><?php __d('shop','Shipping Address');?></th>
			<th><?php echo $this->Paginator->sort('lock');?></th>			
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
						if(!empty($shopOrder['ShopOrdersItem'])){
							$lines = array();
							foreach($shopOrder['ShopOrdersItem'] as $orderItem){ 
								$label = '???';
								if(!empty($orderItem['item_title'])){
									$label = $orderItem['item_title']; 
								}elseif(!empty($orderItem['ShopProduct']['code'])){
									$label = $orderItem['ShopProduct']['code']; 
								}elseif(!empty($orderItem['ShopProduct']['id'])){
									$label = sprintf(__d('shop','product id : %s',true),$orderItem['ShopProduct']['id']);
								}else{
									$label = sprintf(__d('shop','item id : %s',true),$orderItem['id']);
								}
								$url = null;
								if(!empty($orderItem['ShopProduct']['model']) && !empty($orderItem['ShopProduct']['foreign_id'])){
									$url = array('plugin'=>'','controller'=>Inflector::tableize($orderItem['ShopProduct']['model']),'action'=>'view',$orderItem['ShopProduct']['foreign_id']);
								}
								if(!empty($url)){
									$lines[] = $html->link($label,$url);
								}else{
									$lines[] = $label;
								}
							} 
							echo implode('<br />',$lines);
						}
						?>&nbsp;</td>
						<td class="discount"><?php echo $shopOrder['ShopOrder']['discount']; ?>&nbsp;</td>
						<td class="discount_prc"><?php echo $shopOrder['ShopOrder']['discount_prc']; ?>&nbsp;</td>
						<td class="sub_total"><?php echo $shopOrder['ShopOrder']['sub_total']; ?>&nbsp;</td>
						<td class="taxes"><?php 
							if(!empty($shopOrder['ShopOrder']['taxes'])){
								$lines = array();
								foreach($shopOrder['ShopOrder']['taxes'] as $taxeName => $taxeAmount){
									$lines[] = $taxeName.' = '.$taxeAmount;
								}
								echo implode('<br />',$lines);
							}
							?>&nbsp;</td>
						<td class="total"><?php echo $shopOrder['ShopOrder']['total']; ?>&nbsp;</td>
						<td class="paid"><?php echo $shopOrder['ShopOrder']['status']; ?>&nbsp;</td>
						<td><?php echo $this->element('address',array('plugin'=>'shop','address'=>$shopOrder['ShopOrder'],'prefix'=>'billing_'))?></td>		
            			<td><?php echo $this->element('address',array('plugin'=>'shop','address'=>$shopOrder['ShopOrder'],'prefix'=>'shipping_'))?></td>		
						<td class="lock"><?php echo $shopOrder['ShopOrder']['lock']; ?>&nbsp;</td>
						<td class="actions">
							<?php //echo $this->Html->link(__('Edit', true), array('action' => 'edit', $shopOrder['ShopOrder']['id']), array('class' => 'edit')); ?>
							<?php echo $this->Html->link(__('View', true), array('action' => 'view', $shopOrder['ShopOrder']['id']), array('class' => 'view')); ?>
							<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $shopOrder['ShopOrder']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $shopOrder['ShopOrder']['id'])); ?>
                            <?php echo $this->Html->link(__('Add Payment', true), array('plugin'=>'shop', 'controller' => 'shop_payments', 'action' => 'add', 'order' => $shopOrder['ShopOrder']['id'])); ?>
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
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders Items', true)), array('controller' => 'shop_orders_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Orders Item', true)), array('controller' => 'shop_orders_items', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Products', true)), array('controller' => 'shop_products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Product', true)), array('controller' => 'shop_products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Payments', true)), array('controller' => 'shop_payments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Payment', true)), array('controller' => 'shop_payments', 'action' => 'add')); ?> </li>
	</ul>
</div>