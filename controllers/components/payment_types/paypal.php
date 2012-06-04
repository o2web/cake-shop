<?php
App::import('Component', 'Shop.Payment');

class PaypalPaymentComponent extends PaymentComponent{
	var $name = "paypal";
	var $components = array('Session','Email','Shop.PaymentFunct');
	
	function initData() {
	}
	
	function listItemPreprocess(){
		//debug($this->settings);
		//$this->set('blob',$this->getBlob());
		if(empty($this->controller->helpers['PaypalIpn.Paypal'])){
			$this->controller->helpers['PaypalIpn.Paypal'] = array(
				'business' => $this->settings['business'],
				'currency_code' => strtoupper(Configure::read('Shop.currency'))
			);
			if(!empty($this->settings['devMode'])){
				$this->controller->helpers['PaypalIpn.Paypal']['test'] = true;
			}
		}
		$this->set('devMode',!empty($this->settings['devMode']));
		//debug($this->payment);
		$buttonData = array(
			'type' => 'cart',
			'return' => array('plugin' => 'shop', 'controller' => 'shop_payments', 'action' => 'finish', $this->payment['ShopPayment']['id'], 'type'=>'paypal'),
			'cancel_return' => array('plugin' => 'shop', 'controller' => 'shop_payments', 'action' => 'cancel', $this->payment['ShopPayment']['id'], 'type'=>'paypal'),
			'notify_url' => array('plugin' => 'shop', 'controller' => 'shop_payments', 'action' => 'response', $this->payment['ShopPayment']['id'], 'type'=>'paypal')
		);
		$taxes = 0;
		$shipping = 0;
		$total = 0;
		$discount = 0;
		foreach($this->payment['ShopOrder'] as $o_num => $order){
			foreach($order['ShopOrdersItem'] as $i_num => $item){
				//debug($item);
				$buttonData['items'][] = array(
					'item_name' => $item['item_title'], 
					'amount' => round($item['final_price'], 2), 
					'quantity' => $item['nb'], 
					'item_number' => $item['product_id']
				);
			}
			$taxes += $order['total_taxes'];
			$shipping += $order['total_shipping'];
			$total += $order['total'];
			$discount += $order['discount'];
			if(!empty($order['billing_first_name'])){
				$buttonData['email'] = $order['billing_email'];
				$buttonData['first_name'] = $order['billing_first_name'];
				$buttonData['last_name'] = $order['billing_last_name'];
				$buttonData['address1'] = $order['billing_address'];
				if(!empty($order['billing_apt'])){
					$buttonData['address1'].= '('.$order['billing_apt'].')';
				}
				$buttonData['city'] = $order['billing_city'];
				$buttonData['state'] = $order['billing_region'];
				$buttonData['country'] = $order['billing_country'];
				$buttonData['lc'] = $order['billing_country'];
				$buttonData['zip'] = $order['billing_postal_code'];
				$buttonData['night_phone_a'] = $order['billing_tel'];
				$buttonData['night_phone_b'] = $order['billing_tel2'];
			}
		}
		$buttonData['tax_cart'] = round($taxes, 2);
		$buttonData['handling_cart'] = $shipping;
		$buttonData['amount'] = $total;
		$buttonData['discount_amount_cart'] = $discount;
		$buttonData['charset'] = Configure::read('App.encoding');
		
		
		$this->set('buttonData',$buttonData);
		parent::listItemPreprocess();
	}
	
	
	function responsePreprocess(){
	    $ipn = ClassRegistry::init("PaypalIpn.InstantPaymentNotification");
		if($ipn->isValid($_POST)){
			$notification = $ipn->buildAssociationsFromIPN($_POST);
			$ipn->saveAll($notification);
			
			if($_POST["payment_status"]=="Completed"){
				if(isset($_POST['test_ipn'])) {
					$this->payment['ShopPayment']['dev_mode'] = 1;
					$data = array(
						'id' => $this->payment['ShopPayment']['id'],
						'dev_mode' => $this->payment['ShopPayment']['dev_mode'],
					);
					$this->ShopPayment->create();
					$this->ShopPayment->save($data);
				}
				$this->PaymentFunct->setStatus($this->payment,"approved");
			}else{
				$this->log('Incomplete payment',LOG_DEBUG);
			}

			/*$conf = Configure::read('Shop.emailAdmin');
			$this->Email->to = $conf['to'];
			$this->Email->subject = "test module paypal";
			$this->Email->sendAs = 'html';
			$this->Email->template = 'shop_test';
			$this->set('payment', $this->payment);
			if(!$this->Email->send()){
				Debugger::log('response email not sent');
			}else{
				Debugger::log('response email sent');
			}*/
		}else{
			$this->log('Invalide Paypal IPN response',LOG_DEBUG);
			$this->log($_POST,LOG_DEBUG);
		}
	}
	
}
?>