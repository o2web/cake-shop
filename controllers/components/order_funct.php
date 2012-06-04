<?php 
class OrderFunctComponent extends Object
{
	var $components = array('Shop.ShopFunct','Acl','Session','Email','Shop.EmailUtils');
	var $controller = null;
	var $ShopOrder = null;
	
	function __construct() {
		parent::__construct();
		
		//////// config ////////
		config('plugins/shop');
		
		//$plugComponents = Configure::read('Shop.plugComponents');
		
		App::import('Lib', 'Shop.ShopConfig');
		$aroProvider = ShopConfig::load('order.ACL.aroProvider');
		
		if(is_array($aroProvider) && !empty($aroProvider['component'])){
			$plugComponents[] = $aroProvider['component'];
		}
		if(!empty($plugComponents)){
			if(!is_array($plugComponents)){
				$plugComponents = array($plugComponents);
			}
			foreach($plugComponents as $component){
				$this->components[] = $component;
			}
			//debug($plugComponents);
			//debug($this->components);
		}
	}
	
	function initShopOrder(){
		if(!$this->ShopOrder){
			$this->ShopOrder = ClassRegistry::init(array('class' => 'Shop.ShopOrder', 'alias' => 'ShopOrder'));
			/*App::import('ShopOrder', 'Shop.ShopOrder');
			$this->ShopOrder = new ShopOrder;*/
		}
		return $this->ShopOrder;
	}
	
	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	function init(&$controller) {
		$this->controller =& $controller;
	}
	
	function getAro(){
		App::import('Lib', 'Shop.ShopConfig');
		$aroProvider = ShopConfig::load('order.ACL.aroProvider');
		//debug($aroProvider);
		if($aroProvider == "session"){
			$Aro = ClassRegistry::init("Aro");
			
			$parent = $Aro->node("Session");
			if(empty($parent)){
				$data = array();
				$data["alias"] = "Session";
				$Aro->create();
				$Aro->save($data);
				$parent = $Aro->node("Session");
			}
			$aro = $Aro->node('Session/'.$this->Session->id());
			if(empty($aro)){
				$data = array();
				$data["parent_id"] = $parent[0]['Aro']['id'];
				$data["alias"] = $this->Session->id();
				$Aro->create();
				$Aro->save($data);
			}
			$aro = 'Session/'.$this->Session->id();
		}elseif(isset($aroProvider['component']) && isset($aroProvider['component'])){
			$aro = $this->ShopFunct->callExternalfunction($aroProvider);
		}
		if(empty($aro)){
			$aro = ShopConfig::load('order.ACL.defaultAro');
		}
		$this->aro = $aro;
		return $aro;
	}
	
	function tcheckAcl($id){
		if($id){
			$aro = $this->getAro();
			if(!$this->Acl->check($aro, array('model'=>'ShopOrder','foreign_key'=>$id))){
				App::import('Lib', 'Shop.ShopConfig');
				$redirect = ShopConfig::load('order.ACL.deniedRedirect');
				if(empty($redirect)){
					$redirect = array('action' => 'permission_denied');
				}
				$this->controller->redirect($redirect);
			}
		}
	}
	function onReadyStatus($order_id){
		/*$this->initShopOrder();
		$this->ShopOrder->setupForFullData();
		$order = $this->ShopOrder->read(null,$order_id);
		
		$emailAdmin = Configure::read('Shop.emailAdmin');
		if(!empty($emailAdmin)){
			$this->send_email_admin($order);
		}*/
	}
	function onOrderedStatus($order_id){
		$this->initShopOrder();
		$this->ShopOrder->setupForFullData();
		$order = $this->ShopOrder->read(null,$order_id);
		$calcul = $this->ShopFunct->calculate($order);
		//debug($calcul);
		$order['ShopOrder'] = array_merge($order['ShopOrder'],$calcul);
		$devMode = $order['ShopOrder']['dev_mode']?true:null;
		
		App::import('Lib', 'Shop.ShopConfig');
		$emailBuyer = ShopConfig::load('emailBuyer',$devMode);
		if(!empty($emailBuyer)){
			$this->send_email_buyer($order);
		}
		
		$emailAdmin = ShopConfig::load('emailAdmin',$devMode);
		if(!empty($emailAdmin)){
			$this->send_email_admin($order);
		}
	}
	
	function send_email_admin($order){
		$this->Email->reset();
		if(Configure::read('Member.siteName')){
			$siteName = Configure::read('Member.siteName');
		}elseif(Configure::read('config.siteName')){
			$siteName = Configure::read('config.siteName');
		}else{
			$siteName = trim(str_replace('http://','',Router::url('/',true)),' /');
		}
		
		$default_conf = array(
			'subject' => __('New Order #{id}',true),
			'to' => $this->EmailUtils->defaultEmail(),
			'sender' => $this->EmailUtils->defaultEmail(),
			'replyTo' => null,
			'sendAs' => 'both', // because we like to send pretty mail
			'template' => 'order_admin',
			//'layout' => null
		);
		
		
		$devMode = $order['ShopOrder']['dev_mode']?true:null;
		App::import('Lib', 'Shop.ShopConfig');
		$conf = ShopConfig::load('emailAdmin',$devMode);
		if(is_array($conf)){
			$conf = array_merge($default_conf,$conf);
		}else{
			$conf = $default_conf;
		}
		$conf['subject'] = str_replace('{id}',$order['ShopOrder']['id'],$conf['subject']);
		
		$this->EmailUtils->setConfig($conf);
		
		$this->EmailUtils->set('order', $order);
		$this->EmailUtils->set('siteName', $siteName);
		
		if(!$this->Email->send()){
			$this->log('Cound not send Email to administrator',LOG_DEBUG);
			return false;
		}else{
			return true;
		}
	}
	
	function send_email_buyer($order){
		$this->Email->reset();
		if(Configure::read('Member.siteName')){
			$siteName = Configure::read('Member.siteName');
		}elseif(Configure::read('config.siteName')){
			$siteName = Configure::read('config.siteName');
		}else{
			$siteName = trim(str_replace('http://','',Router::url('/',true)),' /');
		}
		
		$default_conf = array(
			'subject' => str_replace('%siteName%',$siteName,__('Your order at %siteName%',true)),
			'to' => $order['ShopOrder']['billing_email'],
			'sender' => $this->EmailUtils->defaultEmail(),
			'replyTo' => null,
			'sendAs' => 'both', // because we like to send pretty mail
			'template' => 'order_admin',
			//'layout' => null
		);
		
		
		$devMode = $order['ShopOrder']['dev_mode']?true:null;
		App::import('Lib', 'Shop.ShopConfig');
		$conf = ShopConfig::load('emailBuyer',$devMode);
		if(is_array($conf)){
			$conf = array_merge($default_conf,$conf);
		}else{
			$conf = $default_conf;
		}
		$conf['to'] = $order['ShopOrder']['billing_email'];
		$conf['subject'] = str_replace('{id}',$order['ShopOrder']['id'],$conf['subject']);
		
		$this->EmailUtils->setConfig($conf);
		
		$this->EmailUtils->set('order', $order);
		$this->EmailUtils->set('siteName', $siteName);
		
		if(!$this->Email->send()){
			Debugger::log('Cound not send Email to Buyer');
			return false;
		}else{
			return true;
		}
	}

}
?>