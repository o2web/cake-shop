<?php
class ShopCouponsController extends ShopAppController {

	var $name = 'ShopCoupons';

	function admin_index($promoId = null) {
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(isset($this->data['ShopCoupon']['q']) && strlen(trim($this->data['ShopCoupon']['q'])) > 0) {
			$q = $this->data['ShopCoupon']['q'];
			$this->params['named']['q'] = $q;
		}
		if(!$promoId && !empty($this->params['named']['id'])){
			$promoId = $this->params['named']['id'];
		}	
					
		if($q !== null) {
			$this->paginate['conditions']['OR'] = array('ShopCoupon.code LIKE' => '%'.$q.'%',
														'ShopCoupon.status LIKE' => '%'.$q.'%');
		}
		if(!empty($promoId)){
			$this->paginate['conditions']['shop_promotion_id'] = $promoId;
		}
		$this->set('promoId', $promoId);

		$this->ShopCoupon->recursive = 0;
		$this->set('shopCoupons', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->ShopCoupon->create();
			if ($this->ShopCoupon->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop coupon'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop coupon'));
			}
		}
		$shopPromotions = $this->ShopCoupon->ShopPromotion->find('list');
		$shopOrders = $this->ShopCoupon->ShopOrder->find('list');
		$this->set(compact('shopPromotions', 'shopOrders'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop coupon'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ShopCoupon->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop coupon'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop coupon'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ShopCoupon->read(null, $id);
		}
		$shopPromotions = $this->ShopCoupon->ShopPromotion->find('list');
		$shopOrders = $this->ShopCoupon->ShopOrder->find('list');
		$this->set(compact('shopPromotions', 'shopOrders'));
	}
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'shop coupon'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ShopCoupon->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Shop coupon'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Shop coupon'));
		$this->redirect(array('action' => 'index'));
	}
	
}
?>