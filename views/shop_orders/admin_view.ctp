<div class="shopOrders view">
<h2><?php  __('Shop Order');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['id']; ?>
			&nbsp;
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['created']; ?>
			&nbsp;
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Items'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php 
						if(!empty($shopOrder['ShopOrdersItem'])){
							$lines = array();
							foreach($shopOrder['ShopOrdersItem'] as $orderItem){ 
								$label = '???';
								if(!empty($orderItem['descr'])){
									$label = $orderItem['descr']; 
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
						?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Discount'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['discount']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Discount Prc'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['discount_prc']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Sub Total'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['sub_total']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Taxes'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if(!empty($shopOrder['ShopOrder']['taxes'])){
					$lines = array();
					foreach($shopOrder['ShopOrder']['taxes'] as $taxeName => $taxeAmount){
						$lines[] = $taxeName.' = '.$taxeAmount;
					}
					echo implode('<br />',$lines);
				} ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Total Taxes'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['total_taxes']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Total'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['total']; ?>
			&nbsp;
		</dd>
		
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Billing Adress'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->element('address',array('plugin'=>'shop','address'=>$shopOrder['ShopOrder'],'prefix'=>'billing_'))?>
			&nbsp;
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Shipping Adress'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->element('address',array('plugin'=>'shop','address'=>$shopOrder['ShopOrder'],'prefix'=>'shipping_'))?>
			&nbsp;
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['active']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Lock'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['lock']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopOrder['ShopOrder']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('Edit %s', true), __('Shop Order', true)), array('action' => 'edit', $shopOrder['ShopOrder']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('Delete %s', true), __('Shop Order', true)), array('action' => 'delete', $shopOrder['ShopOrder']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $shopOrder['ShopOrder']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders', true)), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders Items', true)), array('controller' => 'shop_orders_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Orders Item', true)), array('controller' => 'shop_orders_items', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Products', true)), array('controller' => 'shop_products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Product', true)), array('controller' => 'shop_products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Payments', true)), array('controller' => 'shop_payments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Payment', true)), array('controller' => 'shop_payments', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php printf(__('Related %s', true), __('Shop Orders Items', true));?></h3>
	<?php if (!empty($shopOrder['ShopOrdersItem'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Order Id'); ?></th>
		<th><?php __('Product Id'); ?></th>
		<th><?php __('Nb'); ?></th>
		<th><?php __('Comment'); ?></th>
		<th><?php __('Item Price'); ?></th>
		<th><?php __('Item Tax Applied'); ?></th>
		<th><?php __('Active'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($shopOrder['ShopOrdersItem'] as $shopOrdersItem):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $shopOrdersItem['id'];?></td>
			<td><?php echo $shopOrdersItem['order_id'];?></td>
			<td><?php echo $shopOrdersItem['product_id'];?></td>
			<td><?php echo $shopOrdersItem['nb'];?></td>
			<td><?php echo $shopOrdersItem['comment'];?></td>
			<td><?php echo $shopOrdersItem['item_price'];?></td>
			<td><?php echo $shopOrdersItem['item_tax_applied'];?></td>
			<td><?php echo $shopOrdersItem['active'];?></td>
			<td><?php echo $shopOrdersItem['created'];?></td>
			<td><?php echo $shopOrdersItem['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'shop_orders_items', 'action' => 'view', $shopOrdersItem['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'shop_orders_items', 'action' => 'edit', $shopOrdersItem['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'shop_orders_items', 'action' => 'delete', $shopOrdersItem['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $shopOrdersItem['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Orders Item', true)), array('controller' => 'shop_orders_items', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php printf(__('Related %s', true), __('Shop Products', true));?></h3>
	<?php if (!empty($shopOrder['ShopProduct'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Code'); ?></th>
		<th><?php __('Model'); ?></th>
		<th><?php __('Foreign Key'); ?></th>
		<th><?php __('Price'); ?></th>
		<th><?php __('Shipping Req'); ?></th>
		<th><?php __('Tax Applied'); ?></th>
		<th><?php __('Active'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($shopOrder['ShopProduct'] as $shopProduct):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $shopProduct['id'];?></td>
			<td><?php echo $shopProduct['code'];?></td>
			<td><?php echo $shopProduct['model'];?></td>
			<td><?php echo $shopProduct['foreign_id'];?></td>
			<td><?php echo $shopProduct['price'];?></td>
			<td><?php echo $shopProduct['shipping_req'];?></td>
			<td><?php echo $shopProduct['tax_applied'];?></td>
			<td><?php echo $shopProduct['active'];?></td>
			<td><?php echo $shopProduct['created'];?></td>
			<td><?php echo $shopProduct['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'shop_products', 'action' => 'view', $shopProduct['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'shop_products', 'action' => 'edit', $shopProduct['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'shop_products', 'action' => 'delete', $shopProduct['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $shopProduct['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Product', true)), array('controller' => 'shop_products', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php printf(__('Related %s', true), __('Shop Payments', true));?></h3>
	<?php if (!empty($shopOrder['ShopPayment'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Type'); ?></th>
		<th><?php __('Amount'); ?></th>
		<th><?php __('Status'); ?></th>
		<th><?php __('Data'); ?></th>
		<th><?php __('Active'); ?></th>
		<th><?php __('Lock'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($shopOrder['ShopPayment'] as $shopPayment):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $shopPayment['id'];?></td>
			<td><?php echo $shopPayment['type'];?></td>
			<td><?php echo $shopPayment['amount'];?></td>
			<td><?php echo $shopPayment['status'];?></td>
			<td><?php echo $shopPayment['data'];?></td>
			<td><?php echo $shopPayment['active'];?></td>
			<td><?php echo $shopPayment['lock'];?></td>
			<td><?php echo $shopPayment['created'];?></td>
			<td><?php echo $shopPayment['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'shop_payments', 'action' => 'view', $shopPayment['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'shop_payments', 'action' => 'edit', $shopPayment['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'shop_payments', 'action' => 'delete', $shopPayment['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $shopPayment['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Payment', true)), array('controller' => 'shop_payments', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
