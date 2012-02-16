<div class="shopPayments view">
<h2><?php  __('Shop Payment');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopPayment['ShopPayment']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopPayment['ShopPayment']['type']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Amount'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopPayment['ShopPayment']['amount']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopPayment['ShopPayment']['status']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Data'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopPayment['ShopPayment']['data']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopPayment['ShopPayment']['active']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Lock'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopPayment['ShopPayment']['lock']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopPayment['ShopPayment']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopPayment['ShopPayment']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('Edit %s', true), __('Shop Payment', true)), array('action' => 'edit', $shopPayment['ShopPayment']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('Delete %s', true), __('Shop Payment', true)), array('action' => 'delete', $shopPayment['ShopPayment']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $shopPayment['ShopPayment']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Payments', true)), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Payment', true)), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders', true)), array('controller' => 'shop_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('controller' => 'shop_orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php printf(__('Related %s', true), __('Shop Orders', true));?></h3>
	<?php if (!empty($shopPayment['ShopOrder'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Discount'); ?></th>
		<th><?php __('Discount Prc'); ?></th>
		<th><?php __('Sub Total'); ?></th>
		<th><?php __('Taxes'); ?></th>
		<th><?php __('Total Taxes'); ?></th>
		<th><?php __('Total'); ?></th>
		<th><?php __('Amount Paid'); ?></th>
		<th><?php __('Paid'); ?></th>
		<th><?php __('Owner Model'); ?></th>
		<th><?php __('Owner Foreign Key'); ?></th>
		<th><?php __('Billing First Name'); ?></th>
		<th><?php __('Billing Last Name'); ?></th>
		<th><?php __('Billing Enterprise'); ?></th>
		<th><?php __('Billing Adress'); ?></th>
		<th><?php __('Billing Appart Num'); ?></th>
		<th><?php __('Billing City'); ?></th>
		<th><?php __('Billing Region'); ?></th>
		<th><?php __('Billing Postal Code'); ?></th>
		<th><?php __('Billing Tel'); ?></th>
		<th><?php __('Billing Tel2'); ?></th>
		<th><?php __('Billing Email'); ?></th>
		<th><?php __('Shipping First Name'); ?></th>
		<th><?php __('Shipping Last Name'); ?></th>
		<th><?php __('Shipping Enterprise'); ?></th>
		<th><?php __('Shipping Adress'); ?></th>
		<th><?php __('Shipping Appart Num'); ?></th>
		<th><?php __('Shipping City'); ?></th>
		<th><?php __('Shipping Region'); ?></th>
		<th><?php __('Shipping Postal Code'); ?></th>
		<th><?php __('Shipping Tel'); ?></th>
		<th><?php __('Shipping Tel2'); ?></th>
		<th><?php __('Shipping Email'); ?></th>
		<th><?php __('Active'); ?></th>
		<th><?php __('Lock'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($shopPayment['ShopOrder'] as $shopOrder):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $shopOrder['id'];?></td>
			<td><?php echo $shopOrder['discount'];?></td>
			<td><?php echo $shopOrder['discount_prc'];?></td>
			<td><?php echo $shopOrder['sub_total'];?></td>
			<td><?php echo $shopOrder['taxes'];?></td>
			<td><?php echo $shopOrder['total_taxes'];?></td>
			<td><?php echo $shopOrder['total'];?></td>
			<td><?php echo $shopOrder['amount_paid'];?></td>
			<td><?php echo $shopOrder['paid'];?></td>
			<td><?php echo $shopOrder['owner_model'];?></td>
			<td><?php echo $shopOrder['owner_foreign_id'];?></td>
			<td><?php echo $shopOrder['billing_first_name'];?></td>
			<td><?php echo $shopOrder['billing_last_name'];?></td>
			<td><?php echo $shopOrder['billing_enterprise'];?></td>
			<td><?php echo $shopOrder['billing_adress'];?></td>
			<td><?php echo $shopOrder['billing_appart_num'];?></td>
			<td><?php echo $shopOrder['billing_city'];?></td>
			<td><?php echo $shopOrder['billing_region'];?></td>
			<td><?php echo $shopOrder['billing_postal_code'];?></td>
			<td><?php echo $shopOrder['billing_tel'];?></td>
			<td><?php echo $shopOrder['billing_tel2'];?></td>
			<td><?php echo $shopOrder['billing_email'];?></td>
			<td><?php echo $shopOrder['shipping_first_name'];?></td>
			<td><?php echo $shopOrder['shipping_last_name'];?></td>
			<td><?php echo $shopOrder['shipping_enterprise'];?></td>
			<td><?php echo $shopOrder['shipping_adress'];?></td>
			<td><?php echo $shopOrder['shipping_appart_num'];?></td>
			<td><?php echo $shopOrder['shipping_city'];?></td>
			<td><?php echo $shopOrder['shipping_region'];?></td>
			<td><?php echo $shopOrder['shipping_postal_code'];?></td>
			<td><?php echo $shopOrder['shipping_tel'];?></td>
			<td><?php echo $shopOrder['shipping_tel2'];?></td>
			<td><?php echo $shopOrder['shipping_email'];?></td>
			<td><?php echo $shopOrder['active'];?></td>
			<td><?php echo $shopOrder['lock'];?></td>
			<td><?php echo $shopOrder['created'];?></td>
			<td><?php echo $shopOrder['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'shop_orders', 'action' => 'view', $shopOrder['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'shop_orders', 'action' => 'edit', $shopOrder['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'shop_orders', 'action' => 'delete', $shopOrder['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $shopOrder['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('controller' => 'shop_orders', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
