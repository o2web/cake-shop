<?php $this->Html->script('/shop/js/promo.admin',array('inline'=>false)); ?>
<?php $this->Html->css('/shop/css/shop.admin',null,array('inline'=>false)); ?>
<?php
	$this->Html->scriptBlock('
		(function( $ ) {
			$(function(){
				$("#ShopPromotionAddCoupons, #ShopPromotionCodeNeeded, #ShopPromotionCouponCodeNeeded").change(checkFields);
				$("#ShopPromotionAddCoupons").keypress(checkFields);
				$(".BtAddCoupons").click(function(){
					$(".AddCouponsBox").show();
					$(this).hide();
					return false;
				});
				checkFields();
			})
			function checkFields(){
				if(!$("#ShopPromotionCodeNeeded:checked").length){
					$("#ShopPromotionCouponCodeNeeded").attr("disabled",false);
					$("#ShopPromotionCouponCodeNeeded").closest("div").removeClass("disabled");
				}else{
					$("#ShopPromotionCouponCodeNeeded").closest("div").addClass("disabled");
					$("#ShopPromotionCouponCodeNeeded").attr("disabled",true);
					$("#ShopPromotionCouponCodeNeeded").val(0);
				}
				if(!$("#ShopPromotionCouponCodeNeeded:checked").length){
					$("#ShopPromotionCodeNeeded").attr("disabled",false);
					$("#ShopPromotionCodeNeeded").closest("div").removeClass("disabled");
				}else{
					$("#ShopPromotionCodeNeeded").closest("div").addClass("disabled");
					$("#ShopPromotionCodeNeeded").attr("disabled",true);
					$("#ShopPromotionCodeNeeded").val(0);
				}
			}
		})( jQuery );
	',array('inline'=>false));
?>
<div class="shopPromotions form">
	<?php echo $this->Form->create('ShopPromotion');?>
		<fieldset>
			<legend><?php printf(__('Edit %s', true), __('Shop Promotion', true)); ?></legend>
			<?php
				echo $this->Form->input('active');
				echo $this->Form->input('id');
				echo $this->Form->input('title_fre');
				echo $this->Form->input('title_eng');
				echo $this->Form->input('desc_fre');
				echo $this->Form->input('desc_eng');
			?>
			<fieldset>
				<legend><?php __('Behavior'); ?></legend>
				<?php
					echo $this->element('admin_promo_examples',array());
				?>
			<?php
				$rebateHelp = $this->element('admin_promo_rebate_help',array());
				echo $this->Form->input('val',array('label'=>__('Rebate',true),'after'=>$rebateHelp));
				App::import('Lib', 'Shop.SetMulti');
				echo $this->Form->input('operator',array('options'=>SetMulti::extractKeepKey('label',$operators)));
				if(!empty($actions)){
					echo $this->Form->input('action_id',array('empty'=>__('None',true)));
					?>
					<div id="ActionSubForm" class="ajaxContainer"><div class="loader"></div></div>
					<?php
				}
				echo $this->Form->input('code');
				echo $this->Form->input('code_needed',array('label'=>'Code needed','after'=>'<div class="note">'.__('Buyers needs to enter the code in order to benefit from the promotion',true).'</div>'));
				$coupon_code_needed = $this->Form->input('coupon_code_needed',array('label'=>'Individal coupons','after'=>'<div class="note">'.__('Each coupon has an individual code the buyers needs to enter in order to benefit from the promotion. You will need to print or email each coupon.',true).'</div>'));
			?>
				<div class="couponsResume">
					<?php if( $promotion['ShopPromotion']['coupon_code_needed']) { ?>
					<h3><?php __('Coupons'); ?></h3>
					<!--p><?php __('Used'); ?> : <?php echo $coupons['used'].'/'.$coupons['all'] ?></p-->
					<p><?php __('Reserved'); ?> : <?php echo $coupons['reserved'].'/'.$coupons['all'] ?></p>
					<?php } ?>
					<a href="#" class="BtAddCoupons"><?php __('Add Coupons'); ?></a>
				</div>
				<div class="AddCouponsBox" style="display:none">
			<?php
				echo $this->Form->input('add_coupons',array('label'=>'Qty Coupons'));
				if(empty($coupons['all'])) echo $coupon_code_needed;
			?>
				</div>
			<?php
				if(!empty($coupons['all'])) echo $coupon_code_needed;
			?>
			</fieldset>
			<?php
				echo $this->Form->input('aroProduct',array('label'=>'Choose Products','options'=>$products,'empty'=>__('None',true),'after'=>'<div class="note">'.__('Choisir le produit concerné par la promotion, ou tous les produits.<br>S\'il y a plus d\'un produit, il faut créer plusieurs promos.',true).'</div>'));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('ShopPromotion.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('ShopPromotion.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Promotions', true)), array('action' => 'index'));?></li>
	</ul>
</div>