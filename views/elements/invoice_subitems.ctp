<?php foreach($orderItem['SubItem'] as $orderSubItem){ ?>
	<tr class="item itemSubItem">
		<td>&nbsp;</td>
		<td><?php 
		if($orderSubItem['nb']>1){ 
			if($orderItem['nb']>1){ 
				echo $orderItem['nb'] . '*';
			}
			if($orderSubItem['nb']>1){ 
				echo $orderSubItem['nb'];
			}
		}
		?></td>
		<td>
			<?php if(!empty($orderSubItem['ShopSubproduct']['code'])) echo '('.$orderSubItem['ShopSubproduct']['code'] . ')' ?> 
			<?php echo $orderSubItem['descr']; ?>
		</td>
		<?php  ?>
		<td class="amount"><?php 
			if($orderSubItem['item_price']){
				echo $this->Shop->currency(
					($orderSubItem['item_operator'] == "=")?
						$orderSubItem['item_price']
					:
						$orderSubItem['modif']
				,$currency);
			}
		?></td>
		<td class="amount">&nbsp;</td>
	</tr>
<?php } ?>