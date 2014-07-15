<?php if(isset($order)){
	if(!isset($edit)) $edit = true;
?>
<div class="invoice">
    <div class="general">
        <div><span class="label"><?php __('# Order : ') ?></span><?php echo str_pad($order['ShopOrder']['id'], 6, "0", STR_PAD_LEFT); ?></div>
        <div><span class="label"><?php __('Date : ') ?></span><?php 
		if(!empty($order['ShopOrder']['date'])){
			echo $order['ShopOrder']['date']; 
		}else{
			echo date_('jS F Y H:i');
		}
		?></div>
        <?php 
			//$cleConfirmation = str_split($invoice['Invoice']['confirm_no'], 4);
			//$cleConfirmation = implode(" ", $cleConfirmation);	
		?>
        <?php /*?><div><span class="label"><?php __('Confirmation Number : ') ?></span><?php echo $cleConfirmation; ?></div><?php */?>
		<div class="address billing">
			<h4><?php __d('shop','Billing address') ?> <?php if( $edit ) { ?>(<a href="<?php echo $html->url(array('action'=>'billing',$order['ShopOrder']['id'])) ?>"><?php __d('shop','edit') ?></a>)<?php }?> :</h4>
			<p><?php echo $this->element('address',array('plugin'=>'shop','address'=>$order['ShopOrder'],'prefix'=>'billing_'))?></p>
		</div>
		<?php if(!empty($order['ShopOrder']['shipping_address'])){ ?>
		<div class="address shipping">
			<h4><?php __d('shop','Shipping address') ?> <?php if( $edit ) { ?>(<a href="<?php echo $html->url(array('action'=>'shipping',$order['ShopOrder']['id'])) ?>"><?php __d('shop','edit') ?></a>)<?php }?> :</h4>
			<p><?php echo $this->element('address',array('plugin'=>'shop','address'=>$order['ShopOrder'],'prefix'=>'shipping_'))?></p>
		</div>
		<?php } ?>
    </div>
    
	<?php echo $this->element('invoice_table',array('plugin'=>'shop'))?>
</div>

<?php } ?>