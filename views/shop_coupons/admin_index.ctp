<?php 
$urlOptions = array();
if(isset($promoId)){
	$urlOptions["id"] = $promoId;
}
if(isset($this->data['q'])){
	$urlOptions["q"] = $this->data['q']; 
}
$paginator->options(array('url' => $urlOptions)); 
?>
<div class="shopCoupons index">
	<?php
		echo $this->Form->create('Shop Coupon', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Shop Coupons');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('code');?></th>			
			<th><?php echo $this->Paginator->sort('shop_promotion_id');?></th>			
			<th><?php echo $this->Paginator->sort('shop_order_id');?></th>			
			<th><?php echo $this->Paginator->sort('status');?></th>			
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true), null => __('No', true));
			foreach ($shopCoupons as $shopCoupon) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $shopCoupon['ShopCoupon']['id']; ?>&nbsp;</td>
						<td class="code"><?php echo $shopCoupon['ShopCoupon']['code']; ?>&nbsp;</td>
						<td>
							<?php echo $this->Html->link($shopCoupon['ShopPromotion']['title_fre'], array('plugin'=>'shop', 'controller' => 'shop_promotions', 'action' => 'edit', $shopCoupon['ShopPromotion']['id'])); ?>
						</td>
						<td>
							<?php echo $this->Html->link($shopCoupon['ShopOrder']['id'], array('plugin'=>'shop', 'controller' => 'shop_orders', 'action' => 'edit', $shopCoupon['ShopOrder']['id'])); ?>
						</td>
						<td class="status"><?php echo $shopCoupon['ShopCoupon']['status']; ?>&nbsp;</td>
						<td class="actions">
							<?php //echo $this->Html->link(__('Edit', true), array('action' => 'edit', $shopCoupon['ShopCoupon']['id']), array('class' => 'edit')); ?>
							<?php //echo $this->Html->link(__('Delete', true), array('action' => 'delete', $shopCoupon['ShopCoupon']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $shopCoupon['ShopCoupon']['id'])); ?>
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
<!--div class="actions">
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Coupon', true)), array('action' => 'add')); ?></li>		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Promotions', true)), array('controller' => 'shop_promotions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Promotion', true)), array('controller' => 'shop_promotions', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders', true)), array('controller' => 'shop_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('controller' => 'shop_orders', 'action' => 'add')); ?> </li>
	</ul>
</div-->