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
				echo $this->Form->input('val',array('label'=>__('Price',true)));
				App::import('Lib', 'Shop.SetMulti');
				echo $this->Form->input('operator',array('options'=>SetMulti::extractKeepKey('label',$operators)));
				if(!empty($actions)){
					echo $this->Form->input('action_id',array('empty'=>__('None',true)));
					?>
					<div id="ActionSubForm" class="ajaxContainer"><div class="loader"></div></div>
					<?php
				}
				echo $this->Form->input('code');
				echo $this->Form->input('code_needed',array('label'=>__('Buyers needs to enter the code in order to benefit from the promotion',true)));
				echo $this->Form->input('aroProduct',array('options'=>$products,'empty'=>__('None',true)));
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Promotions', true)), array('action' => 'index'));?></li>
	</ul>
</div>