<?php
class ShopOrdersController extends ShopAppController {

	var $name = 'ShopOrders';
	var $helpers = array('Shop.Shop');
	var $components = array('Shop.OrderFunct','Shop.ShopFunct','Shop.OrderMaker','Acl','Email'/*,'Customforms.CForm'*/);
	var $uses = array('Shop.ShopOrder','Shop.ShopTax',/*'Customforms.CustomForm'*/);
	
	var $aro;
	
	function __construct() {
		parent::__construct();
		$plugComponents = Configure::read('Shop.plugComponents');
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
	
	function beforeFilter(){
		parent::beforeFilter();
		$this->subMenu = $this->Nodes->menu(6, 'flat');
	}
	
	/*
	function index() {
		$this->ShopOrder->recursive = 0;
		$this->set('shopOrders', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop order'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('shopOrder', $this->ShopOrder->read(null, $id));
	
	}
	function test(){
		$this->autoRender = false;
		$test = array(
			'shipping_name' => '1',
			'test' => '1'
		);
		debug($this->ShopOrder->filterExposedfields($test));
	}
	*/
	
	function add($id=null) {
		
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		
		$this->set('lang', $this->lang);
		$options = array();
		if(isset($this->params['named']['product'])){
			$product = array();
			$product['key'] = $this->params['named']['product'];
			if(!empty($this->params['named']['nb']) && floor($this->param['named']['nb'])>0){
				$product['nb'] = floor($this->params['named']['nb']);
			}
			$options['products'][] = $product;
		}
		$options['id'] = $id;
		$order_id = $this->OrderMaker->add($options);
		if($order_id){
			$this->ShopOrder->setupForFullData();
			$order = $this->ShopOrder->read(null,$order_id);
			//debug($order['ShopProduct']);
			//exit();
			
			$needData = false;
			foreach($order['ShopProduct'] as $product){
				if(!empty($product['needed_data'])){
					$needed = array();
					$namedNeeded = array();
					$product = $this->ShopOrder->ShopProduct->tcheckResults($product);
					foreach($product['needed_data'] as $key=>$data){
						if(is_numeric($key)){
							$needed[] = $data;
						}else{
							$namedNeeded[$key] = $data;
						}
					}
					$product = $this->ShopOrder->ShopOrdersItem->tcheckResults($product);
					if(!empty($needed) && (empty($product['ShopOrdersItem']['data']) || count(array_diff($needed,array_keys($product['ShopOrdersItem']['data']))))){
						$needData = true;
						break;
					}elseif(isset($namedNeeded['custom_form_id']) && empty($product['ShopOrdersItem']['data']['custom_form_records_id'])){
						$needData = true;
						break;
					}
					
				}
			}
			if($needData){
				$this->redirect(array('action' => 'data', $order_id, 'lang'=>$this->lang));
			}
			
			$needShipping = false;
			foreach($order['ShopProduct'] as $product){
				if($product['shipping_req']){
					$needShipping = true;
					break;
				}
			}
			if(Configure::read('Shop.groupShippingBilling')){
				if(($needShipping && empty($order['ShopOrder']['shipping_address'])) ||
				   (Configure::read('Shop.billingAddressRequired') && empty($order['ShopOrder']['billing_address']))){
					$this->redirect(array('action' => 'shipping_billing', $order_id, 'lang'=>$this->lang));
				}
			}else{
				if($needShipping && empty($order['ShopOrder']['shipping_address'])){
					$this->redirect(array('action' => 'shipping', $order_id, 'lang'=>$this->lang));
				}
				
				if(Configure::read('Shop.billingAddressRequired') && empty($order['ShopOrder']['billing_address'])){
					$this->redirect(array('action' => 'billing', $order_id, 'lang'=>$this->lang));
				}
			}
			if(empty($order['ShopOrder']['confirm'])){
				$this->redirect(array('action' => 'confirm', $order_id, 'lang'=>$this->lang));
			}
			
			//////////////////// Finalize order ////////////////////
			if($order['ShopOrder']['status']=='input'){
				$dataSource = $this->ShopOrder->getDataSource();
				$data = array();
				$data['id'] = $order_id;
				$data['active'] = 1;
				$data['date'] = $dataSource->expression('NOW()');
				$data = array_merge($data,$this->_calculate($order));
				$order['ShopOrder'] = array_merge($order['ShopOrder'],$data);
				
				
				$data['status'] = 'ready';
				$this->ShopOrder->save($data);
				$this->OrderMaker->statusUpdated($order_id,$data['status']);
				$order['ShopOrder'] = array_merge($order['ShopOrder'],$data);
			}
			
			if($order['ShopOrder']['status']=='ready'){
				if(Configure::read('Shop.payment.enabled')){//add condition when payment is added
					$this->redirect(array('controller' => 'shop_payments','action' => 'add', 'order'=>$order_id, 'lang'=>$this->lang));
				}else{
					$data = array();
					$data['id'] = $order_id;
					$data['status'] = 'ordered';
					$this->ShopOrder->save($data);
					$this->OrderMaker->statusUpdated($order_id,$data['status']);
					$order['ShopOrder'] = array_merge($order['ShopOrder'],$data);
				}
			}
			
			if($order['ShopOrder']['status']=='ordered'){
				$this->redirect(array('action' => 'ordered', $order_id, 'lang'=>$this->lang));
			}
			
		}
	}
	
	function empty_error() {
	}
	
	function data($id = null) {
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		$submited = false;
		if (!empty($this->data)) {
			$submited = true;
			$id = $this->data['ShopOrder']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop order'));
			$this->redirect(array('action' => 'index', 'lang'=>$this->lang));
		}
		$this->OrderFunct->tcheckAcl($id);
		if($submited){
			foreach($this->data['ShopOrdersItem'] as $shopOrdersItem){
				$this->ShopOrder->ShopOrdersItem->recursive = -1;
				$oldData = $this->ShopOrder->ShopOrdersItem->find('first',array('fields'=>array('id','data'),'conditions'=>array('id'=>$shopOrdersItem['id'],'order_id'=>$id)));
				if(!empty($oldData)){
					$data = array();
					$data['id'] = $shopOrdersItem['id'];
					if(empty($shopOrdersItem['data'])){
						$shopOrdersItem['data'] = array();
					}
					$data['data'] = array_merge($oldData['ShopOrdersItem']['data'],$shopOrdersItem['data']);
					$data['data']['custom_form_records_id'] = $this->CForm->CustomFormRecord->id;//BUG!!! peu juste avoir 1 custom form.
					$this->ShopOrder->ShopOrdersItem->save($data);
				}
			}
			$this->redirect(array('action' => 'add', $id, 'lang'=>$this->lang));
		}
		$this->ShopOrder->setupForFullData();
		$order = $this->ShopOrder->read(null,$id);
		if(!$submited){
			$this->data = $order;
		}
		
		$i = 0;
		$productsNeededData = array();
		foreach($order['ShopProduct'] as $product){
			if(!empty($product['needed_data'])){
				$needed = array();
				$namedNeeded = array();
				$product = $this->ShopOrder->ShopProduct->tcheckResults($product);
				foreach($product['needed_data'] as $key=>$data){
					if(is_numeric($key)){
						$needed[] = $data;
					}else{
						$namedNeeded[$key] = $data;
					}
				}
				$product = $this->ShopOrder->ShopOrdersItem->tcheckResults($product);
				$neededData = array('fields'=>$needed,'product'=>$product);
				if(isset($namedNeeded['custom_form_id'])){
					$neededData['custom_form'] = $this->CustomForm->get($namedNeeded['custom_form_id']);
				}
				$productsNeededData[$i] = $neededData;
				if(!$submited){
					$this->data['ShopOrdersItem'][$i]['data'] = $product['ShopOrdersItem']['data'];
				}
				$i++;
			}
		}
		$this->set('productsNeededData',$productsNeededData);
	}
	
	function billing($id = null) {
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if (!empty($this->data)) {
			$id = $this->data['ShopOrder']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop order'));
			$this->redirect(array('action' => 'index', 'lang'=>$this->lang));
		}
		$this->OrderFunct->tcheckAcl($id);
		if (!empty($this->data)) {
			//$this->dispatchComponentCallback('billing_save');
			$data = $this->data['ShopOrder'];
			$prefix = "billing_";
			foreach($data as $key => $val){
				if($key != 'id' && $key != 'use_shipping' && substr($key,0,strlen($prefix)) != $prefix){
					unset($data[$key]);
				}
			}
			$this->ShopOrder->save($data);
			$this->redirect(array('action' => 'add', $id, 'lang'=>$this->lang));
		}else{
			$this->ShopOrder->checkActive = false;
			$this->ShopOrder->recursive = -1;
			$order = $this->ShopOrder->read(null,$id);
			$this->data = $order;
		}
		
		
	}
	
	function shipping($id = null) {
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if (!empty($this->data)) {
			$id = $this->data['ShopOrder']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop order'));
			$this->redirect(array('action' => 'index'));
		}
		$this->OrderFunct->tcheckAcl($id);
		if (!empty($this->data)) {
			//$this->dispatchComponentCallback('shipping_save');
			$data = $this->data['ShopOrder'];
			$prefix = "shipping_";
			foreach($data as $key => $val){
				if($key != 'id' && $key != 'use_billing' && substr($key,0,strlen($prefix)) != $prefix){
					unset($data[$key]);
				}
			}
			$this->ShopOrder->save($data);
			$this->redirect(array('action' => 'add', $id, 'lang'=>$this->lang));
		}else{
			$this->ShopOrder->checkActive = false;
			$this->ShopOrder->recursive = -1;
			$order = $this->ShopOrder->read(null,$id);
			$this->data = $order;
		}
	}
	
	function shipping_billing($id = null) {
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if (!empty($this->data)) {
			$id = $this->data['ShopOrder']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop order'));
			$this->redirect(array('action' => 'index'));
		}
		$this->OrderFunct->tcheckAcl($id);
		if (!empty($this->data)) {
			//$this->dispatchComponentCallback('shipping_save');
			//$this->dispatchComponentCallback('billing_save');
			$data = $this->data['ShopOrder'];
			$pattern = '/(^id$)|(^use_shipping$)|(^use_billing$)|(^shipping_)|(^billing_)|(^both_)/';
			App::import('Lib', 'Shop.SetMulti');
			$data = SetMulti::pregFilterKey($pattern,$data);
			$this->ShopOrder->save($data);
			$this->redirect(array('action' => 'add', $id, 'lang'=>$this->lang));
		}else{
			$this->ShopOrder->checkActive = false;
			$this->ShopOrder->recursive = -1;
			$order = $this->ShopOrder->read(null,$id);
			$this->data = $order;
		}
	}
	
	function _calculate($order){
		return $this->ShopFunct->calculate($order);
	}
	
	function confirm($id = null){
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if (!empty($this->data['ShopOrder']['id'])) {
			$id = $this->data['ShopOrder']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop order'));
			$this->redirect(array('action' => 'index', 'lang'=>$this->lang));
		}
		$this->OrderFunct->tcheckAcl($id);
		
		$this->ShopOrder->setupForFullData();
		$order = $this->ShopOrder->read(null,$id);
		if (!empty($this->data)) {
			if(isset($this->data['ShopOrder']['confirm']) && $this->data['ShopOrder']['confirm']){
				$data['id'] = $id;
				$data['confirm'] = 1;
				$this->ShopOrder->save($data);
				$order['ShopOrder']['confirm'] = 1;
			}
			if($order['ShopOrder']['confirm'] == 1){
				$this->redirect(array('action' => 'add', $id, 'lang'=>$this->lang));
			}else{
				$this->Session->setFlash(__('You must validate you order', true));
			}
		}
		$this->data = $order;
		$calcul = $this->_calculate($order);
		//debug($calcul);
		$order['ShopOrder'] = array_merge($order['ShopOrder'],$calcul);
		$this->set('order',$order);
	}
	
	function _send_email_admin($order){
		return $this->OrderFunct->send_email_admin($order);
	}
	
	function _send_email_buyer($order){
		return $this->OrderFunct->send_email_buyer($order);
	}
	
	function ordered($id = null){
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if (!empty($this->data['ShopOrder']['id'])) {
			$id = $this->data['ShopOrder']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop order'));
			$this->redirect(array('action' => 'index', 'lang'=>$this->lang));
		}
		$this->OrderFunct->tcheckAcl($id);
		
		$this->ShopOrder->setupForFullData();
		$order = $this->ShopOrder->read(null,$id);
		$this->data = $order;
		$this->set('order',$order);
	}
	
	function print_invoice($id = null){
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if (!empty($this->data['ShopOrder']['id'])) {
			$id = $this->data['ShopOrder']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop order'));
			$this->redirect(array('action' => 'index', 'lang'=>$this->lang));
		}
		$this->OrderFunct->tcheckAcl($id);
		
		$this->ShopOrder->setupForFullData();
		$order = $this->ShopOrder->read(null,$id);
		$this->data = $order;
		$this->set('order',$order);
	}
	
	function permission_denied() {

	}

	
	function admin_index() {
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(isset($this->data['Artist']['q']) && strlen(trim($this->data['Artist']['q'])) > 0) {
			$q = $this->data['Artist']['q'];
			$this->params['named']['q'] = $q;
		}
					
		if($q !== null) {
			$this->paginate['conditions']['OR'] = array('ShopOrder.billing_first_name LIKE' => '%'.$q.'%',
														'ShopOrder.billing_last_name LIKE' => '%'.$q.'%',
														'ShopOrder.billing_enterprise LIKE' => '%'.$q.'%',
														'ShopOrder.billing_adress LIKE' => '%'.$q.'%',
														'ShopOrder.billing_appart_num LIKE' => '%'.$q.'%',
														'ShopOrder.billing_city LIKE' => '%'.$q.'%',
														'ShopOrder.billing_region LIKE' => '%'.$q.'%',
														'ShopOrder.billing_postal_code LIKE' => '%'.$q.'%',
														'ShopOrder.billing_tel LIKE' => '%'.$q.'%',
														'ShopOrder.billing_tel2 LIKE' => '%'.$q.'%',
														'ShopOrder.billing_email LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_first_name LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_last_name LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_enterprise LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_adress LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_appart_num LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_city LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_region LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_postal_code LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_tel LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_tel2 LIKE' => '%'.$q.'%',
														'ShopOrder.shipping_email LIKE' => '%'.$q.'%');
		}

		
		$this->ShopOrder->setupForFullData();
		$this->paginate['order'] = 'ShopOrder.created DESC';
		$this->set('shopOrders', $this->paginate());
	}

	function admin_refresh($order_id){
		//$this->autoRender = false;
		$this->OrderMaker->refresh($order_id);
		$this->render('/elements/sql_dump');
	}
		
	/*function admin_add() {
		if (!empty($this->data)) {
			$this->ShopOrder->create();
			if ($this->ShopOrder->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop order'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop order'));
			}
		}
		$shopProducts = $this->ShopOrder->ShopProduct->find('list');
		$shopPayments = $this->ShopOrder->ShopPayment->find('list');
		$this->set(compact('shopProducts', 'shopPayments'));
	}*/
	
	 

	/*function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop order'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ShopOrder->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop order'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop order'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ShopOrder->read(null, $id);
		}
		$shopProducts = $this->ShopOrder->ShopProduct->find('list');
		$shopPayments = $this->ShopOrder->ShopPayment->find('list');
		$this->set(compact('shopProducts', 'shopPayments'));
	}*/

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'shop order'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ShopOrder->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Shop order'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Shop order'));
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_view($id = null){
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shopOrder'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('shopOrder', $this->ShopOrder->read(null, $id));
	}
	
}
?>