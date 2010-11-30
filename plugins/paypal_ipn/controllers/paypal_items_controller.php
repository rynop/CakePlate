<?php
class PaypalItemsController extends PaypalIpnAppController {

	var $name = 'PaypalItems';
	var $helpers = array('Html', 'Form', 'Number', 'Time');

	function admin_index() {
		$this->PaypalItem->recursive = 0;
		$this->set('paypalItems', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid PaypalItem.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('paypalItem', $this->PaypalItem->read(null, $id));
	}

	function admin_add(){
	   $this->redirect(array('admin' => true, 'action' => 'edit')); 
	}

	function admin_edit($id = null) {
		if (!empty($this->data)) {
			if ($this->PaypalItem->save($this->data)) {
				$this->Session->setFlash(__('The PaypalItem has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The PaypalItem could not be saved. Please, try again.', true));
			}
		}
		if ($id && empty($this->data)) {
			$this->data = $this->PaypalItem->read(null, $id);
		}
		$instantPaymentNotifications = $this->PaypalItem->InstantPaymentNotification->find('list');
		$this->set(compact('instantPaymentNotifications'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for PaypalItem', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->PaypalItem->del($id)) {
			$this->Session->setFlash(__('PaypalItem deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>