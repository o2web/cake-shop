<?php
	$this->Html->scriptBlock('
		(function( $ ) {
			$(function(){
				$("#ShopCartIndexForm .btUpdate").hide();
				$("#ShopCartIndexForm input").change(function(){
					$("#ShopCartIndexForm .btUpdate").show();
				});
				$("#ShopCartIndexForm input").keyup(function(){
					$("#ShopCartIndexForm .btUpdate").show();
				});
				$("#ShopCartIndexForm a.BtShowCodePromo").click(function(){
					$("#ShopCartIndexForm a.BtShowCodePromo").hide();
					$("#ShopCartIndexForm .promotionCodes").show();
					return false;
				});
			});
		})( jQuery );
	',array('inline'=>false));
	
	if( !empty($prevUrl) && is_array($prevUrl)) { 
		$prevUrl = $this->Html->url($prevUrl);
	}
?>

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
						<td class="Price"><?php echo $this->Shop->fullprice($cartItem); ?>&nbsp;</td>
						<td class="Amount"><?php echo $this->Cart->qteInput($no,array('div'=>false,'label'=>false,'class'=>'qte')); ?></td>
						<td class="Total"><?php echo $this->Shop->currency($itemTotal); ?></td>
						<td class="actions">
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
			<td class="actions"><?php echo $this->Form->submit(__('Update',true),array('div'=>array('class'=>"submit btUpdate"))); ?></td>
		</tr>
		<?php if( array_key_exists('total_shipping',$calcul) ) { ?>
		<tr class="rowtotal rowtotal_shipping">
			<td colspan="<?php echo $nbCols-3 ?>">&nbsp;</td>
			<td class="price_shipping"><?php __('shipping:'); ?></td>
			<td class="price"><?php echo $this->Shop->currency($calcul['total_shipping']); ?></td>
			<td class="">&nbsp;</td>
		</tr>
		<?php }?>
	</table>
	<?php
		if($codeInput){
			$nb = 0;
			if(!empty($this->data['ShopOrder']['promo_codes'])){
				$nb = count($this->data['ShopOrder']['promo_codes']);
			}
			?>
				<?php if(!$nb) { ?>
					<a href="#promotionCodes" class="BtShowCodePromo"><?php __('If you have a promotional code, click here.'); ?></a>
				<?php }?>
				<fieldset class="promotionCodes"<?php if(!$nb)echo ' style="display:none;"';  ?>>
				<legend><?php __('Promotion Code') ?></legend>
			<?php
			$nbField = $nb+1;
			$max = ShopConfig::load('promo.max');
			if(!empty($max)){
				$nbField = min($max,$nbField);
			}
			for ($i = 0; $i < $nbField; $i++) {
				$opt = array('label'=>false);
				if($i < $nb && isset($codesValidation[$this->data['ShopOrder']['promo_codes'][$i]])){
					if($codesValidation[$this->data['ShopOrder']['promo_codes'][$i]]){
						$opt['after'] = '<span class="valid">'.__('Valid',true).'</span>';
					}else{
						$opt['after'] = '<span class="invalid">'.__('Invalide',true).'</span>';
					}
				}
				echo $this->Form->input('ShopOrder.promo_codes.'.$i,$opt);
			}
			echo $this->Form->submit(__('Validate',true));
			?>
				</fieldset>
			<?php
		}
	?>
	<?php
		if( !empty($prevUrl) ) { 
			echo $this->Form->input('redirect',array('type'=>'hidden','value'=>$prevUrl));
		}
	?>
	<div class="actions">
		<ul>
			<?php if( !empty($prevUrl) ) { ?>
			<li><a href="<?php echo $prevUrl ?>" class="btBack"><?php __('Continue Shopping'); ?></a></li>
			<?php }?>
			<?php if(!empty($cartItems)){ ?>
			<li><?php echo $this->Html->link(__('Order Now',true), array('action' => 'order_now'),array('class'=>'btNextStep'));?></li>
			<?php } ?>
		</ul>
	</div>
	<?php echo $this->Form->end(null); ?>
	<?php }else{ ?>
	<p><?php __('Your cart is empty.'); ?></p>
	<?php } ?>
	
</div>
