<?php
class ShopCartController extends ShopAppController {

	var $name = 'ShopCart';
	var $helpers = array('Shop.Shop', 'Shop.Cart');
	var $components = array('Shop.CartMaker','Shop.ShopFunct');
	var $uses = array();
	
	function index() {
		$prevUrl = null;
		if(!empty($_SERVER['HTTP_REFERER']) && !$this->ShopFunct->isInternalUrl($_SERVER['HTTP_REFERER'])){
			$prevUrl = $_SERVER['HTTP_REFERER'];
		}
		if(!empty($this->params['named']['redirect'])){
			$prevUrl = $this->params['named']['redirect'];
		}
		
		if(!empty($this->data)){
			if(!empty($this->data['ShopCart']['redirect'])){
				$prevUrl = $this->data['ShopCart']['redirect'];
			}
			if(!empty($this->data['ShopCart']['order']['shipping_postal_code'])){
				$location = $this->CartMaker->gessLocation($this->data['ShopCart']['order']['shipping_postal_code']);
				if(!empty($location)){
					foreach($location as $key=>$val){
						if(!empty($val)){
							$this->data['ShopCart']['order']['shipping_'.$key] = $val;
						}
					}
				}
			}
			//debug($this->data);
			$this->CartMaker->save($this->data);
		}else{
			//debug($this->data);
		}
		$this->data = $this->CartMaker->toData();
		
		$data = $this->CartMaker->calculate();
		$cartItems = $data['items'];
		$calcul = $data;
		unset($calcul['items']);
		$this->set('cartItems',$cartItems);
		$this->set('calcul',$calcul);
		
		$this->Component->triggerCallback('shopCartBeforeRender', $this);
		
		$this->set('prevUrl',$prevUrl);
		if(isset($this->params['named']['display']) && $this->params['named']['display'] == 'print'){
			$this->layout = 'print';
			$this->render('print_cart');
		}
	}
	
	function add($model=null, $id = null, $nb = 1){
		if(!$model && isset($this->params['named']['model'])) {
			$model = $this->params['named']['model'];
		}
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if(!$nb && isset($this->params['named']['nb']) && is_numeric($this->params['named']['nb'])) {
			$nb = $this->params['named']['nb'];
		}
		if(!empty($this->data['ShopCart']['nb'])){
			$nb = $this->data['ShopCart']['nb'];
		}
		if(!empty($this->data['ShopCart']['model'])){
			$model = $this->data['ShopCart']['model'];
		}
		if(!empty($this->data['ShopCart']['id'])){
			$id = $this->data['ShopCart']['id'];
		}
		$SubItem = array();
		if(!empty($this->data['ShopCart']['SubItem'])){
			$SubItem = $this->data['ShopCart']['SubItem'];
		}
		
		if (!$id || !$model) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'product'));
			$this->redirect(array('action' => 'index'));
		}
		$this->CartMaker->add(array('products'=>array('model'=>$model,'foreign_id'=>$id,'nb'=>$nb, 'lang'=>$this->lang, 'SubItem'=>$SubItem)));
	}
	
	function clear(){
		$this->CartMaker->clear();
		$this->redirect(array('action' => 'index'));
	}
	function remove($num=null){
		if(!is_null($num)){
			$this->CartMaker->remove($num);
		}
		$this->redirect(array('action' => 'index'));
	}
	function order_now(){
		if(!empty($this->data)){
			$this->CartMaker->save($this->data);
		}
		$this->CartMaker->order_now();
	}
	
	function change_qty($id = null, $qty = "+1"){
		if(!$id && isset($this->params['named']['id']) && is_numeric($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
		}
		if(isset($this->params['named']['qty'])) {
			$qty = $this->params['named']['qty'];
		}
		
		if(!empty($this->data)){
			if(isset($this->data['ShopCart']['id'])){ $id = $this->data['ShopCart']['id']; }
			if(isset($this->data['ShopCart']['qty'])){ $qty = $this->data['ShopCart']['qty']; }
		}
		$this->CartMaker->changeQty($id,$qty);
		
		$this->redirect(array('action' => 'index'));
	}
}