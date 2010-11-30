<?php
class InstantPaymentNotificationsController extends PaypalIpnAppController {

	var $name = 'InstantPaymentNotifications';
	var $helpers = array('Html', 'Form');
	var $components = array('Auth','Email');
	
	/************
	  * beforeFilter makes sure the process is allowed by auth
	  *  since paypal will need direct access to it.
	  */
	function beforeFilter(){
	  parent::beforeFilter();
	  $this->Auth->allow('process');
	  if(isset($this->Security) && $this->action == 'process'){
	    $this->Security->validatePost = false;
	  }
	}
	
	/*****************
	  * Paypal IPN processing action..
	  * This action is the intake for a paypal_ipn callback performed by paypal itself.
	  * This action will take the paypal callback, verify it (so trickery) and save the transaction into your database for later review
	  *
	  * @access public
	  * @author Nick Baker
	  */
	function process(){
	  if($this->InstantPaymentNotification->isValid($_POST)){
      $notification = $this->InstantPaymentNotification->buildAssociationsFromIPN($_POST);
      $this->InstantPaymentNotification->saveAll($notification);
      $this->__processTransaction($this->InstantPaymentNotification->id);
	  }
	  $this->redirect('/');
  }
  
  /*************************
    * __processTransaction is a private callback function used to log a verified transaction
    * @access private
    * @param String $txnId is the string paypal ID and the id used in your database.
    */
  private function __processTransaction($txnId){
    $this->log("Processing Trasaction: $txnId",'paypal');
    //Put the afterPaypalNotification($txnId) into your app_controller.php
    $this->afterPaypalNotification($txnId);
  }
	
	/**
	  * Admin Only Functions... all baked
	  */
	
	/**
	  * Admin Index
	  */
	function admin_index() {	  
		$this->InstantPaymentNotification->recursive = 0;
		$this->set('instantPaymentNotifications', $this->paginate());
	}

	/**
	  * Admin View
	  * @param String ID of the transaction to view
	  */
	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid InstantPaymentNotification.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('instantPaymentNotification', $this->InstantPaymentNotification->read(null, $id));
	}
	
	/**
	  * Admin Add
	  */
	function admin_add(){
	   $this->redirect(array('admin' => true, 'action' => 'edit')); 
	}

	/**
	  * Admin Edit
	  * @param String ID of the transaction to edit
	  */
	function admin_edit($id = null) {
		if (!empty($this->data)) {
			if ($this->InstantPaymentNotification->save($this->data)) {
				$this->Session->setFlash(__('The InstantPaymentNotification has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The InstantPaymentNotification could not be saved. Please, try again.', true));
			}
		}
		if ($id && empty($this->data)) {
			$this->data = $this->InstantPaymentNotification->read(null, $id);
		}
	}

	/**
	  * Admin Delete
	  * @param String ID of the transaction to delete
	  */
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for InstantPaymentNotification', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->InstantPaymentNotification->del($id)) {
			$this->Session->setFlash(__('InstantPaymentNotification deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}
	
}
?>