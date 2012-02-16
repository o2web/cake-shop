<form action="<?php echo ($devMode?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.paypal.com/cgi-bin/webscr"); ?>" method="post" id="paypalForm" class="clearfix">
	<p><?php __('Passer au paiement sécurisé avec Paypal.'); ?></p>
	<br />
	<input type="hidden" name="cmd" value="_s-xclick">
	<input id="paypalBlob" type="hidden" name="encrypted" value="<?php echo $blob ?>">
	<input id="paypalSubmit" type="submit" border="0" name="submit" value="Paypal" class="btn_envoyer" style="margin-top: 2px;float: left;">
	<div class="paypal_logo"></div>
	
</form>