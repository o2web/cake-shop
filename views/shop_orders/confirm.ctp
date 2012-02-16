<div class="shopOrders form">
	<?php echo $this->Form->create('ShopOrder');?>
		<fieldset>
			<legend><?php __d('shop','Billing address'); ?></legend>
			<?php
				echo $this->element('invoice',array('plugin'=>'shop'));
			?>
            <?php echo $form->input('confirm',array('type'=>'checkbox','label'=>__d('shop','All those informations are valid.',true))) ?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>