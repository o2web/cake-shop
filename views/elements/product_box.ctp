<fieldset>
    <legend><?php __('Product'); ?></legend>
<?php 
	echo $form->input('ShopProduct.code');
	echo $form->input('ShopProduct.price');
	echo $form->input('ShopProduct.shipping_req',array('label'=>__d('shop','shipping required',true),'type'=>'checkbox'));
?>
    <?php /*?><fieldset class="fs_event_prices">
        <legend><?php __('Inscription Prices'); ?></legend>
        <div class="event_prices_editor">
            <div class="menu">
                <ul>
                    <li><a class="add_link"><?php __('Add Price'); ?></a></li>
                </ul>
				<div class="error"></div>
                <br style="clear:both" />
            </div>
            <?php 
            echo str_replace('99999','##id##',$this->element('admin_add_event_prices', array('plugin' => 'inscription','i'=>99999, 'class'=>'model')));
            
            if(!empty($this->data['EventPrice'])){
                foreach($this->data['EventPrice'] as $i => $eventPrice){ 
                    echo $this->element('admin_add_event_prices', array('plugin' => 'inscription','i'=>$i, 'eventPrice'=>$eventPrice));
                }
            }?>
        </div>
    </fieldset><?php */?>
</fieldset>