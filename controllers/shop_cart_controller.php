<?php
class ShopCartController extends ShopAppController {

	var $name = 'ShopCart';
	var $helpers = array('Shop.Shop', 'Shop.Cart');
	var $components = array('Shop.CartMaker','Shop.ShopFunct');
	var $uses = array('Shop.ShopPromotion');
	
	function index() {
		$prevUrl = null;
		if(!empty($_SERVER['HTTP_REFERER']) && !$this->ShopFunct->isInternalUrl($_SERVER['HTTP_REFERER'])){
			$prevUrl = $_SERVER['HTTP_REFERER'];
		}
		if(!empty($this->params['named']['redirect'])){
			App::import('Lib', 'Shop.UrlParam');
			$prevUrl = UrlParam::decode($this->params['named']['redirect']);
		}
		
		if(!empty($this->data)){
			if(!empty($this->data['ShopOrder']['promo_codes'])){
				$tmp = array();
				App::import('Lib', 'Shop.ShopConfig');
				$max = ShopConfig::load('promo.max');
				foreach($this->data['ShopOrder']['promo_codes'] as $code){
					if(!empty($max) && count($tmp) >= $max){
						break;
					}
					if(!empty($code) && (empty($tmp) || !in_array($code,$tmp)) ){
						$tmp[] = $code;
					}
				}
				//debug($tmp);
				unset($this->data['ShopOrder']['promo_codes']);
				$this->CartMaker->data['order']['promo_codes'] = $tmp;
			}
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
		if(!empty($this->data['ShopCart']['order'])){
			$this->data['ShopOrder'] = $this->data['ShopCart']['order'];
			
		}
		//debug($this->data);
		
		$data = $this->CartMaker->calculate();
		$cartItems = $data['items'];
		$calcul = $data;
		unset($calcul['items']);
		$this->set('cartItems',$cartItems);
		$this->set('calcul',$calcul);
		
		$this->Component->triggerCallback('shopCartBeforeRender', $this);
		
		if(isset($this->data['ShopOrder']['promo_codes'])){
			$codesValidation = $this->ShopPromotion->codesExists($this->data['ShopOrder']['promo_codes'],false,true);
			$this->set('codesValidation',$codesValidation);
		}
		$codePromos = $this->ShopPromotion->find('count',array('conditions'=>array('or'=>array('code_needed'=>1,'coupon_code_needed'=>1))));
		$this->set('codeInput',$codePromos>0);
		
		$defaultReturn = ShopConfig::load('cart.defaultReturn');
		if(empty($prevUrl) && !empty($defaultReturn)){
			$prevUrl = $defaultReturn;
		}
		$this->set('prevUrl',$prevUrl);
		if(isset($this->params['named']['display']) && $this->params['named']['display'] == 'print'){
			$this->layout = 'print';
			$this->render('print_cart');
		}
	}
	
	function add($model=null, $id = null, $nb = 1){
		$extract_data = array(
			'products.model' => array('data.ShopCart.model','params.named.model','params.model'),
			'products.foreign_id' => array('data.ShopCart.id','params.named.id','params.id'),
			'products.nb' => array('data.ShopCart.nb','params.named.nb','params.nb'),
			'back_encoded' => array('params.named.back','params.back'),
			'back' => array('data.ShopCart.back'),
		);
		App::import('Lib', 'Shop.SetMulti');
		$source = array('params'=>$this->params, 'data'=>$this->data);
		$opt = SetMulti::extractHierarchicMulti($extract_data,$source);
		if(!empty($opt['back_encoded'])){
			App::import('Lib', 'Shop.UrlParam');
			$opt['back'] = UrlParam::decode($opt['back_encoded']);
			unset($opt['back_encoded']);
		}
		
		if (empty($opt['products']['foreign_id']) || empty($opt['products']['model'])) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'product'));
			$this->redirect(array('action' => 'index'));
		}
		$this->CartMaker->add($opt);
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