<div class="shopCart index">
	<h2><?php __('Your Shopping Cart');?></h2>
	
	<?php 
	$nbCols = 5;
	//debug($cartItems);
	if(!empty($cartItems)){ ?>
	<?php echo $this->Form->create('ShopCart',array('url'=>array('controller'=>'shop_cart')));?>
	<table cellpadding="0" cellspacing="0">
		<tr>		
			<th><?php __('Description');?></th>	
			<?php 
			App::import('Lib', 'Shop.ShopConfig');
			$types = ShopConfig::getSubProductTypes();
			if(!empty($types) && Configure::read('Shop.cart.inlineSubProduct') ) { 
				foreach($types as $key => $type){ ?>
					<th><?php echo $type['label'] ?></th>
				<?php 
					$nbCols++;
				} 
			} ?>
			<th><?php __('Price');?></th>					
			<th><?php __('Amount');?></th>		
			<th><?php __('Total');?></th>		
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$total = 0;
			foreach ($cartItems as $no => $cartItem) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				$itemTotal = $cartItem['DynamicField']['price']*$cartItem['Options']['nb'];
				?>
					<tr<?php echo $class;?>>
						<td class="Description"><?php echo $cartItem['DynamicField']['title']; ?>&nbsp;</td>
						<?php 
						if(!empty($types) && Configure::read('Shop.cart.inlineSubProduct') ) { 
							foreach($types as $key => $type){ ?>
								<td class="SubItem <?php echo $key ?>"><?php echo $this->Cart->subitemInput('Size',$cartItem,array('div'=>false,'label'=>false),$no); ?></td>
							<?php } ?>
						<?php } ?>
						<td class="Price"><?php echo $this->element('qualified_price',array('plugin'=>'shop','product'=>$cartItem))?>&nbsp;</td>
						<td class="Amount"><?php echo $this->Cart->qteInput($no,array('div'=>false,'label'=>false,'class'=>'qte')); ?></td>
						<td class="Total"><?php echo $this->Shop->currency($itemTotal); ?></td>
						<td class="actions">
							<?php echo $this->Form->submit(__('Update',true)); ?>
							<?php echo $this->Html->link(__('Remove', true), array('action' => 'remove', $no), array('class' => 'remove')); ?>
						</td>
					</tr>
				<?php
			}
		?>
		<tr class="rowtotal">
			<td colspan="<?php echo $nbCols-3 ?>">&nbsp;</td>
			<td class="price_total"><?php __('Subtotal:'); ?></td>
			<td class="price"><?php echo $this->Shop->currency($calcul['sub_total']); ?></td>
			<td class="">&nbsp;</td>
		</tr>
	</table>
	<?php echo $this->Form->end(null); ?>
	<?php }else{ ?>
	<p><?php __('Your cart is empty.'); ?></p>
	<?php } ?>
	<div class="actions">
		<ul>
			<?php 
			//debug($cartItems);
			if(!empty($cartItems)){ ?>
			<li><?php echo $this->Html->link(__('Order Now',true), array('action' => 'order_now'));?></li>
			<?php } ?>
		</ul>
	</div>
	
</div>
