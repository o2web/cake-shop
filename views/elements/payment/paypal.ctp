<p><?php __('Passer au paiement sécurisé avec Paypal.'); ?></p>

<?php //debug($buttonData);  ?>
<?php echo $paypal->button(__('Checkout', true), $buttonData);  ?>