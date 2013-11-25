<?php
class ShopPaymentsController extends ShopAppController {

	var $name = 'ShopPayments';
	var $helpers = array('Shop.Shop');
	var $components = array('Shop.ShopFunct','Shop.OrderMaker','Acl','Shop.PaymentFunct');
	
	function __construct() {
		parent::__construct();
		$plugComponents = Configure::read('Shop.plugComponents');
		$aroProvider = Configure::read('Shop.address.ACL.aroProvider');
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
	
	/*function index() {
		$this->ShopPayment->recursive = 0;
		$this->set('shopPayments', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop payment'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('shopPayment', $this->ShopPayment->read(null, $id));
	}*/

	function __getAro(){
		$aroProvider = Configure::read('Shop.payment.ACL.aroProvider');
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
			$aro = Configure::read('Shop.payment.ACL.defaultAro');
		}
		$this->aro = $aro;
		return $aro;
	}
	
	function __tcheckAcl($id){
		if($id){
			$aro = $this->__getAro();
			if(!$this->Acl->check($aro, array('model'=>$this->ShopPayment->alias,'foreign_key'=>$id))){
				$redirect = Configure::read('Shop.payment.ACL.deniedRedirect');
				if(empty($redirect)){
					$redirect = array('controller'=>'shop_orders','action' => 'permission_denied');
				}
				$this->redirect($redirect);
			}
		}
	}

	function add($id=null) {
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		$orders = array();
		if(isset($this->params['named']['order'])){
			$conditions = array();
			$conditions['active'] = 1;
			$conditions['id'] = $this->params['named']['order'];
			$this->ShopPayment->ShopOrder->recursive = -1;
			$order = $this->ShopPayment->ShopOrder->find('first',array('conditions'=>$conditions));
			$orders[] = $order;
		}
		if(!$id && empty($orders)){
			trigger_error(__("ShopOrdersController::add() - order not found", true), E_USER_ERROR);
		}
		if(!$id){
			$data = array();
			$this->ShopPayment->create();
			$data['status'] = 'input';
			$data['active'] = 1;
			App::import('Lib', 'Shop.ShopConfig');
			$data['currency'] = !empty($orders[0]['ShopOrder']['currency'])?$orders[0]['ShopOrder']['currency']:ShopConfig::load('currency');
			$this->ShopPayment->save($data);
			$payment_id = $this->ShopPayment->id;
			$aro = $this->__getAro();
			$this->Acl->allow($aro, $this->ShopPayment);
		}else{
			$payment_id = $id;
			$this->__tcheckAcl($payment_id);
		}
		foreach($orders as $order){
			$data = array();
			$this->ShopPayment->ShopOrdersPayment->create($data);
			$data['order_id'] = $order['ShopOrder']['id'];
			$data['amount'] = $order['ShopOrder']['total'];
			$data['payment_id'] = $payment_id;
			$this->ShopPayment->ShopOrdersPayment->save($data);
		}
		if(!$id){
			$this->redirect(array('action' => 'add', $payment_id, 'lang'=>$this->lang));
		}else{
			$this->ShopPayment->Behaviors->attach('Containable');
			$this->ShopPayment->contain(array(
					'ShopOrdersPayment',
					'ShopOrder'=>array(
						'ShopOrdersItem'
					)
				));
			$this->ShopPayment->ShopOrder->ShopProduct->fullDataEnabled = false;
			$payment = $this->ShopPayment->read(null,$payment_id);
			$availableTypes = Configure::read('Shop.payment.available');
			$types = array();
			foreach($availableTypes as $type){
				$component = $this->PaymentFunct->loadPaimentComponent($type);
				$component->payment = $payment;
				$types[$type] = $component->getData('listItem');
			}
			$this->set("types",$types);
		}
	}
	
	function response($id=null){
		$this->autoRender=false;
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if(!empty($id)){
			$payment = $this->ShopPayment->read(null,$id);
			if(!empty($payment)){
				if($payment['ShopPayment']['type']){
					$type = $payment['ShopPayment']['type'];
				}else{
					if(!empty($this->params['named']['type'])) {
						$type = $this->params['named']['type'];
					}
				}
				if(!empty($type)){
					$component = $this->PaymentFunct->loadPaimentComponent($type);
					$component->payment = $payment;
					$options = $component->getData('response');
				}else{
					Debugger::log('Payment for the Response does not have a type');
				}
			}else{
				Debugger::log('Could not get payment for the Response');
			}
		}else{
			Debugger::log('Response does not have a payment id');
		}
	}
	
	function returning($id=null){
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		
		$defaultOptions = array(
			'redirect' => array('action'=>'add',$id)
		);
		
		$payment = $this->ShopPayment->read(null,$id);
		if(!empty($payment) && in_array($payment['ShopPayment']['status'],$this->ShopPayment->okStatus)){
			$defaultOptions['redirect'] = array('action'=>'finish',$id);
		}
		
		$this->_typedView($payment,'finish',$defaultOptions);
	}
	
	function cancel($id=null){
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		$defaultOptions = array(
			'redirect' => array('action'=>'add',$id),
			'save' => array('type'=>null)
		);
		$this->_typedView($id,'cancel',$defaultOptions);
	}
	
	function finish($id=null){
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		$defaultOptions = array(
			'redirect' => array('action'=>'add',$id)
		);
		
		$payment = $this->ShopPayment->read(null,$id);
		if(!empty($payment) && !empty($payment['ShopOrder'])){
			$defaultOptions['redirect'] = array('plugin'=>'shop','controller'=>'shop_orders','action'=>'add','id'=>$payment['ShopOrder'][0]['id']);
		}
		$this->_typedView($payment,'finish',$defaultOptions);
	}
	
	function _typedView($payment,$action,$defaultOptions = array()){
		if(is_numeric($payment)){
			$payment = $this->ShopPayment->read(null,$payment);
		}
		$options = array();
		if(!empty($payment)){
			if($payment['ShopPayment']['type']){
				$type = $payment['ShopPayment']['type'];
			}else{
				if(!empty($this->params['named']['type'])) {
					$type = $this->params['named']['type'];
				}
			}
			if(!empty($type)){
				$component = $this->PaymentFunct->loadPaimentComponent($type);
				$component->payment = $payment;
				$options = $component->getData($action);
			}
		}
		$options = array_merge($defaultOptions,$options);
		if(!empty($options['save'])){
			$this->ShopPayment->save($options['save']);
		}
		if(!empty($options['redirect'])){
			$this->redirect($options['redirect']);
		}
		if(!empty($options['element'])){
			$this->set("type",$options);
			$this->render("typed");
		}
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
			$this->paginate['conditions']['OR'] = array('ShopPayment.type LIKE' => '%'.$q.'%',
														'ShopPayment.status LIKE' => '%'.$q.'%',
														'ShopPayment.data LIKE' => '%'.$q.'%');
		}

		$this->ShopPayment->recursive = 0;
		$this->set('shopPayments', $this->paginate());
	}

		
	function admin_add() {
		if (!empty($this->data)) {
			$this->ShopPayment->create();
			$total = 0;
			foreach($this->data['ShopOrdersPayment'] as $ordersPayment){
				$total += $ordersPayment['amount'];
			}
			$this->data['ShopPayment']['amount'] = $total;
			if ($this->ShopPayment->save($this->data)) {
				foreach($this->data['ShopOrdersPayment'] as $ordersPayment){
					$this->ShopPayment->ShopOrdersPayment->create();
					$ordersPayment['payment_id'] = $this->ShopPayment->id;
					$this->ShopPayment->ShopOrdersPayment->save($ordersPayment);
					$this->OrderMaker->refresh($ordersPayment['order_id']);
				}
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop payment'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop payment'));
			}
		}else{
			$orders_id = array();
			if(!empty($this->params['named']['order'])){
				$orders_id[] = $this->params['named']['order'];
			}
			if(!empty($orders_id)){
				$this->ShopPayment->ShopOrder->recursive = -1;
				$orders = $this->ShopPayment->ShopOrder->find('all',array('fields'=>array('id','total','amount_paid'),'conditions'=>array('ShopOrder.id'=>$orders_id)));
				$this->data['ShopOrdersPayment'] = array();
				foreach($orders as $order){
					$ordersPayment = array();
					$ordersPayment['order_id'] = $order['ShopOrder']['id'];
					$ordersPayment['amount'] = $order['ShopOrder']['total'] - $order['ShopOrder']['amount_paid'];
					$this->data['ShopOrdersPayment'][] = $ordersPayment;
				}
			}
		}
		$this->set('types',array_combine(array_keys($this->ShopPayment->types),array_keys($this->ShopPayment->types)));
		$this->set('status',array_combine($this->ShopPayment->status,$this->ShopPayment->status));
		$shopOrders = $this->ShopPayment->ShopOrder->find('list');
		$this->set(compact('shopOrders'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop payment'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ShopPayment->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop payment'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop payment'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ShopPayment->read(null, $id);
		}
		$shopOrders = $this->ShopPayment->ShopOrder->find('list');
		$this->set(compact('shopOrders'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'shop payment'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ShopPayment->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Shop payment'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Shop payment'));
		$this->redirect(array('action' => 'index'));
	}
	
}
?>