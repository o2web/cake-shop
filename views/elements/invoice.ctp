<?php if(isset($order)){
	$currency = !empty($order['ShopOrder']['currency'])?$order['ShopOrder']['currency']:null;
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
    <table class="details" cellspacing="<?php echo !empty($cellspacing)?$cellspacing:0 ?>" cellpadding="<?php echo !empty($cellpadding)?$cellpadding:0 ?>">
        <tr>
            <th><?php __d('shop','# ref') ?></th>
            <th><?php __d('shop','Qte') ?></th>
            <th><?php __d('shop','Descr') ?></th>
            <th><?php __d('shop','Unit price') ?></th>
            <th><?php __d('shop','Amount') ?></th>
        </tr>
        <?php 
		//debug($order);
		//$extractData = array('ShopOrder.OrderItem','ShopOrdersItem');
		//App::import('Lib', 'Shop.SetMulti');
		//$data = SetMulti::extractHierarchic($extractData, $order);
		foreach($order['ShopOrder']['OrderItem'] as $orderItem){ 
			//debug($orderItem);
		?>
		
			<tr class="item">
				<td><?php echo str_pad($orderItem['product_id'], 6, "0", STR_PAD_LEFT); ?></td>
				<td><?php echo $orderItem['nb']; ?></td>
				<td><?php echo $orderItem['item_title'];
					if(!empty($orderItem['comment'])){
						echo "<span>".$orderItem[$orderItem['ShopProduct']['code']]."</span>"; 
					}
				?></td>
				<td class="amount"><?php if(empty($orderItem['overwritten_price'])) echo $this->Shop->currency($orderItem['item_alone_price']-$orderItem['item_rebate'],$currency); ?></td>
				<td class="amount"><?php echo $this->Shop->currency($orderItem['total'],$currency); ?></td>
			</tr>
			
			<?php if(!empty($orderItem['SubItem'])){ ?>
				<?php foreach($orderItem['SubItem'] as $orderSubItem){ ?>
					<tr class="item itemSubItem">
						<td>&nbsp;</td>
						<td><?php 
						if($orderItem['nb']>1){ 
							echo $orderItem['nb'];
							if($orderItem['nb']>1){ 
								echo '*';
							}
						}
						if($orderItem['nb']>1){ 
							echo $orderItem['nb'];
						}
						?></td>
						<td><?php echo $orderSubItem['descr']; ?></td>
						<?php  ?>
						<td class="amount"><?php echo $this->Shop->currency(
							($orderSubItem['item_operator'] == "=")?
								$orderSubItem['item_price']
							:
								$orderSubItem['modif']
						,$currency); ?></td>
						<td class="amount">&nbsp;</td>
					</tr>
				<?php } ?>
			<?php } ?>
        <?php } ?>
		
		<?php if(!empty($order['ShopOrder']['discount']) || !empty($order['ShopOrder']['taxes']) || !empty($order['ShopOrder']['total_shipping'])){ ?>
        <tr class="totals sub_total">
            <td colspan="4" class="title"><?php __d('shop','Sub total') ?></td>
            <td class="amount"><?php echo $this->Shop->currency($order['ShopOrder']['sub_total'],$currency); ?></td>
        </tr>
		<?php } ?>
        <?php if(!empty($order['ShopOrder']['discount'])){ ?>
        <tr class="totals discount">
            <td colspan="4" class="title"><?php __('Discount') ?></td>
            <td class="amount"><?php echo $this->Shop->currency($order['ShopOrder']['discount'],$currency); ?></td>
        </tr>
        <?php } ?>
		
		<?php 
		$totalTaxedSupplements = 0;
		if(!empty($order['ShopOrder']['supplements'])){
			foreach($order['ShopOrder']['supplements'] as $supplementName => $supplement){  
				if( !empty($supplement['tax_applied'])){	
					$totalTaxedSupplements += $supplement['total'];
				}
			}
			echo $this->element('invoice_supplements',array('plugin'=>'shop','supplements'=>$order['ShopOrder']['supplements'],'taxed'=>true,'total'=>&$totalTaxedSupplements));
		}
		if(!empty($order['ShopOrder']['taxes'])){
			$lastSub = $order['ShopOrder']['sub_total'] + $totalTaxedSupplements;
			foreach($order['ShopOrder']['taxes'] as $taxeName => $taxeAmount){ 
				$taxeSub = $order['ShopOrder']['taxe_subs'][$taxeName];
				$taxePrc = $taxeAmount / $taxeSub *100;
				if($taxeSub != $lastSub){
		?>		
        <tr class="totals taxes_sub">
            <td colspan="4" class="title"><?php __('Taxable Amount'); ?></td>
            <td class="amount"><?php echo $this->Shop->currency($order['ShopOrder']['taxe_subs'][$taxeName],$currency); ?></td>
        </tr>
		<?php	 } ?>
		
        <tr class="totals taxes">
            <td colspan="4" class="title"><?php echo $taxeName; ?> <span class="prc">(<?php echo $taxePrc ?> %)</span></td>
            <td class="amount"><?php echo $this->Shop->currency($taxeAmount,$currency); ?></td>
        </tr>
        <?php 
				$lastSub = $taxeSub + $taxeAmount;
			} 
		} ?>
		<?php 
		if(!empty($order['ShopOrder']['supplements'])){
			echo $this->element('invoice_supplements',array('plugin'=>'shop','supplements'=>$order['ShopOrder']['supplements'],'taxed'=>false));
		}
		?>
        <tr class="totals total">
            <td colspan="4" class="title"><?php __d('shop','Total') ?></td>
            <td class="amount"><?php echo $this->Shop->currency($order['ShopOrder']['total'],$currency); ?></td>
        </tr>
    </table>
</div>

<?php } ?>