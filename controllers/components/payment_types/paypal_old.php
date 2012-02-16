<?php
App::import('Component', 'Shop.Payment');

class PaypalPaymentComponent extends PaymentComponent{
	var $name = "paypal";
	var $components = array('Session');
	
	function initData() {
		if (stristr(PHP_OS, 'WIN')) {
			$this->os = 'windows';
		}
		if(!empty($this->settings['devMode'])){
			$this->devMode();
		}
		$this->tempFileDirectory = TMP . DS . 'paypal';
	}
	
	function listItemPreprocess(){
		debug($this->settings);
		$this->set('blob',$this->getBlob());
		$this->set('devMode',!empty($this->settings['devMode']));
		parent::listItemPreprocess();
	}
	
	function getBlob($id = null){
			if(!empty($this->settings['certificate'])){
				$certificate = $this->settings['certificate'];
				$this->setCertificate($certificate['certificateFilename'],$certificate['privateKeyFilename']);
				$this->setCertificateID($certificate['certificateID']);
				$this->setPayPalCertificate($certificate['payPalCertificate']);
			}
			
			$this->setReturn('invoice');
			$this->setCancel('add');
			$this->setIPN('ipn');
			
			$this->setPaypalEmail('smazeas@epiderma.ca');
	
	
			//$order = $this->ShopOrder->read(null,$id);
			//$this->data = $order;
			//var_dump($order);
			//$order['ShopOrder'] = array_merge($order['ShopOrder'],$this->_calculate($order));
			//print_r($order);
			//exit();
		
			//$this->setEmail($order['ShopOrder']['billing_email']);
			//$this->setFirstName($order['ShopOrder']['billing_first_name']);
			//$this->setLastName($order['ShopOrder']['billing_last_name']);
			//$this->setAddress1($order['ShopOrder']['billing_address']);
			
			//if(isset($order['ShopOrder']['billing_apt']) && strlen(trim($order['ShopOrder']['billing_apt'])) > 0) {
			//	$this->setAddress2($order['ShopOrder']['billing_apt']);
			//}

			//$this->setCity($order['ShopOrder']['billing_city']);
			//if(strlen($order['ShopOrder']['billing_region']) ==0)
			//	$this->setProvince("Quebec");
			//$this->setProvince($order['ShopOrder']['billing_region']);
			//if(strlen($order['ShopOrder']['billing_country']) ==0)
			//	$this->setCountry("Canada");
			//$this->setCountry($order['ShopOrder']['billing_country']);
			//$this->setPostalCode($order['ShopOrder']['billing_postal_code']);
			//$this->setPhone1($order['ShopOrder']['billing_tel']);
			
			
			//$this->setTax(round($order['ShopOrder']['total_taxes'],2));
			//$this->setShipping($order['ShopOrder']['total_shipping']);
			//if($order['ShopOrder']['discount'] > 0) {
			//	$this->setRebate($order['ShopOrder']['discount']);
			//}
			
			//$items = array();
			
			//foreach($order['ShopOrdersItem']as $prod) {
			//	$this->addItem($prod['descr'], $prod['id'], $prod['nb'], $prod['item_price']);
			//	
			//	$items[] = array(
			//		'model' => 'Product',
			//		'model_conditions' => array('id' => $prod['id']),
			//		'name_fre' => $prod['descr'],
			//		'desc_fre' =>  $prod['descr'],
			//		'price' => $prod['item_price'],
			//		'nb' => $prod['nb'],
			//		'tax_applied' => array()
			//	);
			//}
			
			
			$this->setOrderId($id);
			$this->Session->write('ORDERNUMBER', $id);
			
			return $this->getEncryptedString();
	}
	
	
	var $devMode = false;
	var $os = 'linux';
	var $paypalEmail = null;
	
	var $certificate;	// Certificate resource
	var $certificateFile;	// Path to the certificate file
	var $privateKey;	// Private key resource (matching certificate)
	var $privateKeyFile;	// Path to the private key file
	var $paypalCertificate;	// PayPal public certificate resource
	var $paypalCertificateFile;	// Path to PayPal public certificate file
	var $certificateID; // ID assigned by PayPal to the $certificate.
	var $tempFileDirectory;
	
	var $pass = array(
		'cmd' => '_cart',
		'bn' => 'PP-BuyNowBF',
		'upload' => '1',
		'currency_code' => 'CAD',
		'no_shipping' => '1',
		'lc' => 'CA',
		'country_code' => 'CA'
	);
	
	var $currentItemIndex = 1;
	
	
	function devMode($tf = true) {
		$this->devMode = $tf;
	}
	
	function setParam($key, $value) {
		$this->pass[$key] = $value;
	}
	
	function setReturn($action) {
		$this->pass['return'] = Router::url(array('action' => $action), true);
	}
	
	function setIPN($action) {
		$this->pass['notify_url'] = Router::url(array('action' => $action), true);
	}
	
	function setCancel($action) {
		$this->pass['cancel_return'] = Router::url(array('action' => $action), true);
	}
	
	function setEmail($value) {
		$this->pass['email'] = $value;
	}
	
	function setFirstName($value) {
		$this->pass['first_name'] = $value;
	}
	
	function setLastName($value) {
		$this->pass['last_name'] = $value;
	}
	
	function setAddress1($value) {
		$this->pass['address1'] = $value;
	}
	
	function setAddress2($value) {
		$this->pass['address2'] = $value;
	}
	
	function setCity($value) {
		$this->pass['city'] = $value;
	}
	
	function setProvince($value) {
		$this->pass['state'] = $value;
	}
	
	function setCountry($value) {
		$this->pass['country'] = $value;
	}
	
	function setPostalCode($value) {
		$this->pass['zip'] = $value;
	}
	
	function setPhone1($value) {
		$this->pass['night_phone_a'] = $value;
	}
	
	function setPhone2($value) {
		$this->pass['night_phone_b'] = $value;
	}
	
	function setPaypalEmail($value) {
		$this->paypalEmail = $value;
		$this->pass['business'] = $value;
	}
	
	function setOrderId($value) {
		$this->pass['invoice'] = $value;
	}
	
	function setTax($value) {
		$this->pass['tax_cart'] = $value;
	}
	
	function setShipping($value) {
		$this->pass['handling_cart'] = $value;
	}
	
	function setRebate($value) {
		$this->pass['discount_amount_cart'] = $value;
	}
	
	function addItem($name, $number, $qty, $price) {
		$this->pass['item_name_' . $this->currentItemIndex] = $name;
		$this->pass['item_number_' . $this->currentItemIndex] = $number;
		$this->pass['quantity_' . $this->currentItemIndex] = $qty;
		$this->pass['amount_' . $this->currentItemIndex] = $price;
		
		$this->currentItemIndex = $this->currentItemIndex + 1;
	}
	
	function getEncryptedString() {
		//return "-----BEGIN PKCS7-----" . str_replace("\n", "", $this->encryptButton($params)) . "-----END PKCS7-----";
		return "-----BEGIN PKCS7-----" . $this->encryptButton($this->pass) . "-----END PKCS7-----";
	}
	
	/*
		setCertificate: set the client certificate and private key pair.
		$certificateFilename - The path to the client certificate
		$keyFilename - The path to the private key corresponding to the certificate

		Returns: TRUE iff the private key matches the certificate.
	*/
	function setCertificate($certificateFilename, $privateKeyFilename) {
		$result = FALSE;
		
		$certificateFilename = APP . 'paypal' . DS . $certificateFilename;
		$privateKeyFilename = APP . 'paypal' . DS . $privateKeyFilename;

		if (is_readable($certificateFilename) && is_readable($privateKeyFilename)) {
			
			
			if($this->os == 'windows') {
				$certificate = null;
				$handle = fopen($certificateFilename, "r");
				$size = filesize($certificateFilename);
				$certificate = fread($handle,$size);
				fclose($handle);

				$privateKey = null;              
				$handle = fopen($privateKeyFilename,"r");
				$size = filesize($privateKeyFilename);
				$privateKey = fread($handle, $size);
				fclose($handle);

				if (($certificate !== false) && ($privateKey !== false) && openssl_x509_check_private_key($certificate, $privateKey)) {
					$this->certificate = $certificate;
					$this->certificateFile = $certificateFilename;
					$this->privateKey = $privateKey;
					$this->privateKeyFile = $privateKeyFilename;
					$result = true;
				}
			} else {
				$certificate = @openssl_x509_read(file_get_contents($certificateFilename));
				$privateKey = openssl_get_privatekey(file_get_contents($privateKeyFilename));

				if (($certificate !== FALSE) &&	($privateKey !== FALSE) && openssl_x509_check_private_key($certificate, $privateKey)) {
					$this->certificate = $certificate;
					$this->certificateFile = $certificateFilename;

					$this->privateKey = $privateKey;
					$this->privateKeyFile = $privateKeyFilename;

					$result = TRUE;
				}
			}
		}

		return $result;
	}
	
	/*
		setPayPalCertificate: Sets the PayPal certificate

		$fileName - The path to the PayPal certificate.

		Returns: TRUE iff the certificate is read successfully, FALSE otherwise.
	*/

	function setPayPalCertificate($fileName) {
		$fileName = APP . 'paypal' . DS . $fileName;
	
		if (is_readable($fileName)) {
			
			
			if($this->os == 'windows') {
				$handle = null;
				$certificate = null;
				$size = null;
				
				$handle = fopen($fileName, "r");
				if (!$handle){
					echo 'Paypal cert could not be opened';
				}
				$size = filesize($fileName);
			
				$certificate = fread($handle, $size);
				
				if (!$certificate){
					echo 'Paypal cert could not be read';
				}
				
				fclose($handle);

				if ($certificate !== false) {
					$this->paypalCertificate = $certificate;
					$this->paypalCertificateFile = $fileName;
					return true;
				}
			}
			else {
				$certificate = @openssl_x509_read(file_get_contents($fileName));
				if ($certificate !== FALSE) {
					$this->paypalCertificate = $certificate;
					$this->paypalCertificateFile = $fileName;

					return TRUE;
				}
			}

			
		}

		return FALSE;
	}
	 
	/*
		setCertificateID: Sets the ID assigned by PayPal to the client certificate
		$id - The certificate ID assigned when the certificate was uploaded to PayPal
	*/
	 
	function setCertificateID($id) {
		$this->certificateID = $id;
	}
	 
	/*
		setTempFileDirectory: Sets the directory into which temporary files are written.
		$directory - Directory in which to write temporary files.
		Returns: TRUE iff directory is usable.
	*/
	 
	function setTempFileDirectory($directory) {
		if (is_dir($directory) && is_writable($directory)) {
			$this->tempFileDirectory = $directory;
			return TRUE;
		} else {
			return FALSE;
		}
	}
	 
	/*
		encryptButton: Using the previously set certificates and tempFileDirectory
		encrypt the button information.

		$parameters - Array with parameter names as keys.

		Returns: The encrypted string for the _s_xclick button form field.
	*/
	 
	function encryptButton($parameters) {
        // Check encryption data is available.

        if (($this->certificateID == '') || !isset($this->certificate) || !isset($this->paypalCertificate)) {
    	    return false;
        }

        $clearText = '';
        $encryptedText = '';
		
		if($this->os == 'windows') {

			// initialize data.
			$data = "cert_id=" . $this->certificateID . "\n";;
			foreach($parameters as $k => $v) 
				$d[] = "$k=$v";
				$data .= join("\n", $d);

			$dataFile = tempnam($this->tempFileDirectory, 'data');
			
			$out = fopen("{$dataFile}_data.txt", 'wb');
			fwrite($out, $data);
			fclose($out);
			
			$out=fopen("{$dataFile}_signed.txt", "w+"); 

			if (!openssl_pkcs7_sign("{$dataFile}_data.txt", "{$dataFile}_signed.txt", $this->certificate, $this->privateKey, array(), PKCS7_BINARY)) {
				return false;
			}
			fclose($out);

			$signedData = explode("\n\n", file_get_contents("{$dataFile}_signed.txt"));

			$out = fopen("{$dataFile}_signed.txt", 'wb');
			fwrite($out, base64_decode($signedData[1]));
			fclose($out);

			if (!openssl_pkcs7_encrypt("{$dataFile}_signed.txt", "{$dataFile}_encrypted.txt", $this->paypalCertificate, array(), PKCS7_BINARY)) {
				return false;
			}

			$encryptedData = explode("\n\n", file_get_contents("{$dataFile}_encrypted.txt"));

			$encryptedText = $encryptedData[1];

			@unlink($dataFile);  
			@unlink("{$dataFile}_data.txt");
			@unlink("{$dataFile}_signed.txt");
			@unlink("{$dataFile}_encrypted.txt");
		
		} else {
			// Compose clear text data.

			$clearText = 'cert_id=' . $this->certificateID;

			foreach (array_keys($parameters) as $key) {
				$clearText .= "\n{$key}={$parameters[$key]}";
			}

			$clearFile = tempnam($this->tempFileDirectory, 'clear_');
			$signedFile = preg_replace('/clear/', 'signed', $clearFile);
			$encryptedFile = preg_replace('/clear/', 'encrypted', $clearFile);

			$out = fopen($clearFile, 'wb');
			fwrite($out, $clearText);
			fclose($out);

			if (!openssl_pkcs7_sign($clearFile, $signedFile, $this->certificate, $this->privateKey, array(), PKCS7_BINARY)) {
				return FALSE;
			}

			$signedData = explode("\n\n", file_get_contents($signedFile));

			$out = fopen($signedFile, 'wb');
			fwrite($out, base64_decode($signedData[1]));
			fclose($out);

			if (!openssl_pkcs7_encrypt($signedFile, $encryptedFile, $this->paypalCertificate, array(), PKCS7_BINARY)) {
				return FALSE;
			}

			$encryptedData = explode("\n\n", file_get_contents($encryptedFile));

			$encryptedText = $encryptedData[1];

			@unlink($clearFile);
			@unlink($signedFile);
			@unlink($encryptedFile);
			//return $clearText;
			
		}
		
		return $encryptedText;
    }
	
	function validateIPN($response) {
		$header = '';
		$req = 'cmd=_notify-validate';

		foreach ($response as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}

		// post back to PayPal system to validate
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		
		if($this->devMode) {
			$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30); // dev
		} else {
			$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30); // prod
		}

		if (!$fp) {
			// HTTP ERROR
			return false;
		} 
		else {
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp ($res, 'VERIFIED') == 0) {
					// check the payment_status is Completed
					// check that txn_id has not been previously processed
					// check that receiver_email is your Primary PayPal email
					// check that payment_amount/payment_currency are correct
					// process payment
					
					if($response['payment_status'] == 'Completed') {
						/*$payment = $this->Payment->find('first', array('conditions' => array('Payment.txn_id' => $response['txn_id'])));
						
						if((empty($payment) || $payment == false) && $response['receiver_email'] == $this->paypalEmail) {
							return true;
						}
						else {
							// bad!
							//$logFile = ROOT . "../paypal/error.log";
							//$fh = fopen($logFile, 'a') or die("can't open file");
							//fwrite($fh, serialize($paypal_response)."\n");
							//fclose($fh);
							return false;
						}*/
						return true;
					}
				}
				else if (strcmp ($res, "INVALID") == 0) {
					// log for manual investigation
					//$logFile = ROOT . "../paypal/error.log";
					//$fh = fopen($logFile, 'a') or die("can't open file");
					//fwrite($fh, serialize($paypal_response)."\n");
					//fclose($fh);
					return false;
				}
			}
			fclose ($fp);
		}
	}
}
?>