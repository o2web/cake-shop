
<?php $this->Html->script('/shop/js/fancybox/jquery.fancybox-1.3.4.pack',array('inline'=>false)); ?>
<?php $this->Html->css('/shop/js/fancybox/jquery.fancybox-1.3.4',null,array('inline'=>false)); ?>
<?php
	$this->Html->scriptBlock('
		(function( $ ) {
			$(function(){
				$(".btRebatehelp").fancybox()
			})
		})( jQuery );
	',array('inline'=>false));
?>
				<a href="#Rebatehelp" class="btRebatehelp"><?php __('Help'); ?></a>
				<div class="hidepopup" >
					<div id="Rebatehelp" class="popup helpPopup">
						<p><strong><?php __('Equal'); ?></strong> : Sélectionnez "equal" pour afficher le produit à un prix fixe. 
						Entrez le prix fixe du produit dans le champ "Rabais" , sans symbole. 
						Ex : 50.99</p>

						<p><strong><?php __('Add'); ?></strong> : Sélectionnez "add" pour offrir un rabais d'un montant fixe. 
						Si vous souhaitez offrir un rabais de 10 $, entrez le montant du rabais précédé du symbole "-". 
						Ex : -10</p>

						<p><strong><?php __('Multiply'); ?></strong> : Sélectionnez "multiply" pour offrir un pourcentage de rabais.
						Si vous souhaitez offrir 15 % de rabais, entrez la fraction du prix qui doit être payée.
						Ex : 0.85</p>
					</div>
				</div>