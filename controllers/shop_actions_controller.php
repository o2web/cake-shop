<?php
class ShopActionsController extends ShopAppController {

	var $name = 'ShopActions';
	var $components = array('Shop.ShopFunct');

	
	function admin_form($id = null) {
		$this->layout = 'ajax';
		$action = $this->ShopAction->read(null, $id);
		if(!empty($action['ShopAction']['ui']['beforeForm'])){
			$this->ShopFunct->callExternalfunction($action['ShopAction']['ui']['beforeForm']);
		}
		if(!empty($action['ShopAction']['form_element'])){
			$this->render('/elements/'.$action['ShopAction']['form_element']);
		}else{
			$this->render(false);
		}
	}
	
	function admin_test(){
		$action = $this->ShopAction->read(null, 4);
		
		debug($action);
		//$action['ShopAction']['ui']['beforeForm'] = array('component'=>'CampaignFunct','function' => 'beforeForm');
		//$action = $this->ShopAction->save($action);
		
		$this->render(false);
	}
	
	function admin_index() {
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(isset($this->data['ShopAction']['q']) && strlen(trim($this->data['ShopAction']['q'])) > 0) {
			$q = $this->data['ShopAction']['q'];
			$this->params['named']['q'] = $q;
		}
					
		if($q !== null) {
			$this->paginate['conditions']['OR'] = array('ShopAction.code LIKE' => '%'.$q.'%',
														'ShopAction.status LIKE' => '%'.$q.'%',
														'ShopAction.component LIKE' => '%'.$q.'%',
														'ShopAction.function LIKE' => '%'.$q.'%',
														'ShopAction.params LIKE' => '%'.$q.'%',
														'ShopAction.form_element LIKE' => '%'.$q.'%',
														'ShopAction.ui LIKE' => '%'.$q.'%');
		}

		$this->ShopAction->recursive = 0;
		$this->set('shopActions', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->ShopAction->create();
			if ($this->ShopAction->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop action'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop action'));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop action'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ShopAction->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop action'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop action'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ShopAction->read(null, $id);
		}
	}
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'shop action'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ShopAction->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Shop action'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Shop action'));
		$this->redirect(array('action' => 'index'));
	}
	
}
?>