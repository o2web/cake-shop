<div class="shopOrders form">
	<?php echo $this->Form->create('ShopOrder');?>
    <?php echo $form->input('id'); ?>
	<?php foreach($productsNeededData as $i=>$neededData){ ?>
    	<div>
        	<h3><?php echo sprintf(__('For %s', true), $neededData['product']['code']); ?></h3>
            <?php 
				echo $this->Form->input('ShopOrdersItem.'.$i.'.id',array('value'=>$neededData['product']['ShopOrdersItem']['id'],'type'=>'hidden'));
				foreach($neededData['fields'] as $field){ 
					echo $this->Form->input('ShopOrdersItem.'.$i.'.data.'.$field);
				}
				if(!empty($neededData['custom_form'])){
					echo $this->CustomForm->display($neededData['custom_form'],array('form'=> false,'redirect' => false));
				}
			?>
        </div>
    <?php } ?>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>