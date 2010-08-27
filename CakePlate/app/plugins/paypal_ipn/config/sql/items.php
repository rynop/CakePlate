<?php
/** 
  * Migration file.  If you do not have paypal_ipn installed on your system. please use the ipn schema file.
  */
class itemsSchema extends CakeSchema {
  var $name = 'items';
  
  function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}  
	
	var $paypal_items = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'instant_payment_notification_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'item_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 127),
		'item_number' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 127),
		'quantity' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 127),
		'mc_gross' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'mc_shipping' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'mc_handling' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'tax' => array('type' => 'float', 'null' => true, 'default' => NULL, 'length' => '10,2'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

}
?>