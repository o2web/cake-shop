<?php $this->Html->script('/shop/js/promo.admin',array('inline'=>false)); ?>
<?php $this->Html->css('/shop/css/shop.admin',null,array('inline'=>false)); ?>
<div class="shopPromotions form">
	<?php echo $this->Form->create('ShopPromotion');?>
		<fieldset>
			<legend><?php printf(__('Add %s', true), __('Shop Promotion', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
				echo $this->Form->input('title_fre');
				echo $this->Form->input('title_eng');
				echo $this->Form->input('desc_fre');
				echo $this->Form->input('desc_eng');
			?>
			<fieldset>
				<legend><?php __('Conditions'); ?></legend>
				<?php
					echo $this->Form->input('aroProduct',array(
						'label'=>__('Choose Products', true),
						'options'=>$products, 
						'multiple'=>true,
						'size'=>"10",
						'empty'=>__('None',true),
						'after'=>'<div class="note">'.__('Choisir le produit concerné par la promotion, ou tous les produits.<br>S\'il y a plus d\'un produit, il faut créer plusieurs promos.',true).'</div>'
					));
				
					if(!empty($conds)){
						echo $this->element('promo_method_def',array(
							'plugin'=>'Shop',
							'methods' => $conds,
							'methodPath'=>'ShopPromotion.cond.0',
							'paramPath'=>'ShopPromotion.cond_params.0',
							'default'=>null,
							'label' => __('Condition',true),
						));
					} ?>
			</fieldset>
			<fieldset>
				<legend><?php __('Behavior'); ?></legend>
				<?php
					echo $this->element('admin_promo_examples',array());
					echo $this->element('promo_method_def',array(
						'plugin'=>'Shop',
						'methods' => $methods,
						'methodPath'=>'ShopPromotion.method',
						'paramPath'=>'ShopPromotion.method_params',
						'default'=>'Shop.operation',
						'label' => __('Type',true),
					));
			?>
			</fieldset>
			<?php if( ShopConfig::load('promo.coupons') ) { ?>
			<fieldset>
				<legend><?php echo __('Code promo',true).' / '.__('Coupons',true); ?></legend>
			<?php
				echo $this->Form->input('code');
				echo $this->Form->input('code_needed',array('label'=>__('Code needed', true),'after'=>'<div class="note">'.__('Buyers needs to enter the code in order to benefit from the promotion',true).'</div>'));
				echo $this->Form->input('add_coupons',array('label'=>__('Qty Coupons', true),'after'=>'<div class="note">'.__('Set this to set how many buyer will be able to use this promotion',true).'</div>'));
				echo $this->Form->input('coupon_code_needed',array('label'=>__('Individal coupons', true),'after'=>'<div class="note">'.__('Each coupon has an individual code the buyers needs to enter in order to benefit from the promotion. You will need to print or email each coupon.',true).'</div>'));
			?>
			</fieldset>
			<?php }?>
			
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Promotions', true)), array('action' => 'index'));?></li>
	</ul>
</div>